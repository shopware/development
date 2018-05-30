<?php declare(strict_types=1);

namespace Shopware\Development;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Shopware\Framework\Struct\Uuid;
use Shopware\PlatformRequest;
use Shopware\Storefront\StorefrontRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

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

        $tenantId = $request->headers->get(PlatformRequest::HEADER_TENANT_ID);

        if (!$this->isApplicationRequired($request->getPathInfo())) {
            return $request;
        }

        $application = $this->findApplication($request);

        if ($application === null) {
            return $request;
        }

        $baseUrl = str_replace($request->getSchemeAndHttpHost(), '', $application['url']);

        $uri = $this->resolveSeoUrl($request, $tenantId, $baseUrl, $application['applicationId']);

        $server = array_merge(
            $_SERVER,
            [
                'REQUEST_URI' => $uri,
                'SCRIPT_NAME' => $baseUrl,
                'SCRIPT_FILENAME' => $baseUrl,
            ]
        );

        $clone = $request->duplicate(null, null, null, null, null, $server);

        $clone->headers->set(PlatformRequest::HEADER_APPLICATION_TOKEN, $application['applicationToken']);
        $clone->attributes->set(
            StorefrontRequest::ATTRIBUTE_IS_STOREFRONT_REQUEST,
            //if set to true, session will be started
            strpos($clone->getRequestUri(), '/storefront-api/') === false
        );
        $clone->headers->set(PlatformRequest::HEADER_TENANT_ID, $tenantId);

        return $clone;
    }

    private function isApplicationRequired(string $pathInfo): bool
    {
        $pathInfo = rtrim($pathInfo, '/') . '/';

        foreach ($this->whitelist as $prefix) {
            if (strpos($pathInfo, $prefix) === 0) {
                return false;
            }
        }

        return true;
    }

    private function findApplication(SymfonyRequest $request)
    {
        $statement = $this->connection->query(
            "SELECT application.id, application.access_key, application.configuration FROM application WHERE type = 'storefront'"
        );

        $applications = $statement->fetchAll();

        if (empty($applications)) {
            return null;
        }

        $requestUrl = rtrim($request->getSchemeAndHttpHost() . $request->getPathInfo(), '/');

        $domains = [];
        foreach ($applications as $application) {
            $configuration = json_decode($application['configuration'], true);

            foreach ($configuration['domains'] as $url) {
                $url['applicationId'] = $application['id'];
                $url['applicationToken'] = $application['access_key'];

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

    private function resolveSeoUrl(Request $request, string $tenantId, string $baseUrl, string $applicationId): string
    {
        $pathInfo = $request->getPathInfo();
        if (!empty($baseUrl) && strpos($pathInfo, $baseUrl) === 0) {
            $pathInfo = substr($pathInfo, strlen($baseUrl));
        }

        $query = $this->connection->prepare(
            'SELECT path_info 
             FROM seo_url 
             WHERE application_id = ? 
             AND seo_path_info = ?
             AND tenant_id = ?
             LIMIT 1'
        );

        $query->execute([$applicationId, ltrim($pathInfo, '/'), Uuid::fromHexToBytes($tenantId)]);

        $url = $query->fetch(\PDO::FETCH_COLUMN);

        if (empty($url)) {
            return $request->getRequestUri();
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
            throw new \RuntimeException('No tenant id header set and no tenant environment configured');
        }

        if (!Uuid::isValid($tenantId)) {
            throw new InvalidUuidStringException(sprintf('Tenant id %s is not a valid uuid string', $tenantId));
        }

        $request->headers->set(PlatformRequest::HEADER_TENANT_ID, $tenantId);
    }
}
