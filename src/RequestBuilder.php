<?php declare(strict_types=1);

namespace Shopware\Development;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Struct\Uuid;
use Shopware\Core\PlatformRequest;
use Shopware\Storefront\StorefrontRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RequestBuilder
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string[]
     */
    private $whitelist = [
        '/_wdt/',
        '/_profiler/',
        '/_error/',
        '/api/',
        '/storefront-api/',
        '/admin/',
    ];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function create(): SymfonyRequest
    {
        $request = SymfonyRequest::createFromGlobals();
        $tenantId = $this->getTenantId($request);

        if (!$this->isSalesChannelRequired($request->getPathInfo())) {
            return $request;
        }

        $salesChannel = $this->findSalesChannel($request, $tenantId);
        if ($salesChannel === null) {
            return $request;
        }

        $baseUrl = str_replace($request->getSchemeAndHttpHost(), '', $salesChannel['url']);

        $uri = $this->resolveSeoUrl($request, $tenantId, $baseUrl, $salesChannel['salesChannelId']);

        $server = array_merge(
            $_SERVER,
            [
                'REQUEST_URI' => $baseUrl . $uri,
                'SCRIPT_NAME' => $baseUrl . '/index.php',
                'SCRIPT_FILENAME' => $baseUrl . '/index.php',
            ]
        );

        $clone = $request->duplicate(null, null, null, null, null, $server);

        $clone->headers->set(PlatformRequest::HEADER_TENANT_ID, $tenantId);
        $clone->attributes->set(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID, Uuid::fromBytesToHex($salesChannel['salesChannelId']));
        $clone->attributes->set(StorefrontRequest::ATTRIBUTE_IS_STOREFRONT_REQUEST, true);

        return $clone;
    }

    private function isSalesChannelRequired(string $pathInfo): bool
    {
        $pathInfo = rtrim($pathInfo, '/') . '/';

        foreach ($this->whitelist as $prefix) {
            if (strpos($pathInfo, $prefix) === 0) {
                return false;
            }
        }

        return true;
    }

    private function findSalesChannel(SymfonyRequest $request, string $tenantId): ?array
    {
        $salesChannels = $this->connection->createQueryBuilder()
            ->select(['sales_channel.id', 'sales_channel.access_key', 'sales_channel.configuration'])
            ->from('sales_channel')
            ->where('sales_channel.type_id = UNHEX(:id)')
            ->andWhere('sales_channel.tenant_id = UNHEX(:tenantId)')
            ->setParameter('id', Defaults::SALES_CHANNEL_STOREFRONT)
            ->setParameter('tenantId', $tenantId)
            ->execute()
            ->fetchAll();

        if (empty($salesChannels)) {
            return null;
        }

        $requestUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBasePath() . $request->getPathInfo(), '/');

        $domains = [];
        foreach ($salesChannels as $salesChannel) {
            $configuration = json_decode($salesChannel['configuration'], true);

            foreach ($configuration['domains'] as $url) {
                $url['salesChannelId'] = $salesChannel['id'];
                $url['salesChannelAccessKey'] = $salesChannel['access_key'];

                $domains[$url['url']] = $url;
            }
        }

        // direct hit
        if (array_key_exists($requestUrl, $domains)) {
            return $domains[$requestUrl];
        }

        // reduce shops to which base url is the beginning of the request
        $domains = array_filter($domains, function ($baseUrl) use ($requestUrl) {
            return strpos($requestUrl, $baseUrl) === 0;
        }, ARRAY_FILTER_USE_KEY);

        if (empty($domains)) {
            return null;
        }

        // determine most matching shop base url
        $lastBaseUrl = '';
        $bestMatch = current($domains);
        foreach ($domains as $baseUrl => $urlConfig) {
            if (\strlen($baseUrl) > \strlen($lastBaseUrl)) {
                $bestMatch = $urlConfig;
            }

            $lastBaseUrl = $baseUrl;
        }

        return $bestMatch;
    }

    private function resolveSeoUrl(Request $request, string $tenantId, string $baseUrl, string $salesChannelId): string
    {
        $pathInfo = $request->getPathInfo();
        if (!empty($baseUrl) && strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        }

        $url = $this->connection->createQueryBuilder()
            ->select('path_info')
            ->from('seo_url')
            ->where('sales_channel_id = :salesChannelId')
            ->andWhere('seo_path_info = :seoPath')
            ->andWhere('tenant_id = :tenantId')
            ->setMaxResults(1)
            ->setParameter('salesChannelId', $salesChannelId)
            ->setParameter('tenantId', Uuid::fromHexToBytes($tenantId))
            ->setParameter('seoPath', ltrim($pathInfo, '/'))
            ->execute()
            ->fetchColumn();

        if (empty($url)) {
            return $request->getPathInfo();
        }

        $uri = $request->getRequestUri();

        if (!empty($baseUrl) && strpos($uri, $baseUrl) === 0) {
            $uri = substr($uri, strlen($baseUrl));
        }

        if (strpos($uri, $pathInfo) === 0) {
            $uri = $url . substr($uri, strlen($pathInfo));
        }

        return $uri;
    }

    private function getTenantId(Request $request): string
    {
        if ($request->headers->has(PlatformRequest::HEADER_TENANT_ID)) {
            return $request->headers->get(PlatformRequest::HEADER_TENANT_ID);
        }

        $tenantId = getenv('TENANT_ID');

        if (!$tenantId) {
            throw new HttpException(Response::HTTP_PRECONDITION_REQUIRED, 'The tenant_id must be present. Please check your environment.');
        }

        if (!Uuid::isValid($tenantId)) {
            throw new HttpException(Response::HTTP_PRECONDITION_FAILED, 'The tenant_id is invalid. Please check your environment.');
        }

        $request->headers->set(PlatformRequest::HEADER_TENANT_ID, $tenantId);

        return $tenantId;
    }
}
