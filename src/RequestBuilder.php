<?php declare(strict_types=1);

namespace Shopware\Development;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
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
     * @var \PDO
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

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(): SymfonyRequest
    {
        $request = SymfonyRequest::createFromGlobals();

        $this->ensureTenantId($request);


        if (!$this->isTouchpointRequired($request->getPathInfo())) {
            return $request;
        }

        $touchpoint = $this->findTouchpoint($request);

        if ($touchpoint === null) {
            return $request;
        }

        $baseUrl = str_replace($request->getSchemeAndHttpHost(), '', $touchpoint['url']);

        $tenantId = $request->headers->get(PlatformRequest::HEADER_TENANT_ID);

        $uri = $this->resolveSeoUrl($request, $tenantId, $baseUrl, $touchpoint['touchpointId']);

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
        $clone->attributes->set(PlatformRequest::ATTRIBUTE_OAUTH_CLIENT_ID, Uuid::fromBytesToHex($touchpoint['touchpointId']));
        $clone->attributes->set(StorefrontRequest::ATTRIBUTE_IS_STOREFRONT_REQUEST, true);

        return $clone;
    }

    private function isTouchpointRequired(string $pathInfo): bool
    {
        $pathInfo = rtrim($pathInfo, '/') . '/';

        foreach ($this->whitelist as $prefix) {
            if (strpos($pathInfo, $prefix) === 0) {
                return false;
            }
        }

        return true;
    }

    private function findTouchpoint(SymfonyRequest $request)
    {
        $statement = $this->connection->query(
            "SELECT touchpoint.id, touchpoint.access_key, touchpoint.configuration FROM touchpoint WHERE type = 'storefront'"
        );

        $touchpoints = $statement->fetchAll();

        if (empty($touchpoints)) {
            return null;
        }

        $requestUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBasePath() . $request->getPathInfo(), '/');

        $domains = [];
        foreach ($touchpoints as $touchpoint) {
            $configuration = json_decode($touchpoint['configuration'], true);

            foreach ($configuration['domains'] as $url) {
                $url['touchpointId'] = $touchpoint['id'];
                $url['touchpointToken'] = $touchpoint['access_key'];

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

    private function resolveSeoUrl(Request $request, string $tenantId, string $baseUrl, string $touchpointId): string
    {
        $pathInfo = $request->getPathInfo();
        if (!empty($baseUrl) && strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        }

        $query = $this->connection->prepare(
            'SELECT path_info 
             FROM seo_url 
             WHERE touchpoint_id = ? 
             AND seo_path_info = ?
             AND tenant_id = ?
             LIMIT 1'
        );

        $query->execute([$touchpointId, ltrim($pathInfo, '/'), Uuid::fromHexToBytes($tenantId)]);

        $url = $query->fetch(\PDO::FETCH_COLUMN);

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

    private function ensureTenantId(Request $request): void
    {
        if ($request->headers->has(PlatformRequest::HEADER_TENANT_ID)) {
            return;
        }

        $tenantId = getenv('TENANT_ID');

        if (!$tenantId) {
            throw new HttpException(Response::HTTP_PRECONDITION_REQUIRED, 'The tenant_id must be present. Please check your environment.');
        }

        if (!Uuid::isValid($tenantId)) {
            throw new HttpException(Response::HTTP_PRECONDITION_FAILED, 'The tenant_id is invalid. Please check your environment.');
        }

        $request->headers->set(PlatformRequest::HEADER_TENANT_ID, $tenantId);
    }
}
