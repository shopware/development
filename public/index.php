<?php

use Doctrine\DBAL\DBALException;
use PackageVersions\Versions;
use Shopware\Core\Framework\Event\BeforeSendResponseEvent;
use Shopware\Core\Framework\Adapter\Cache\CacheIdLoader;
use Shopware\Core\Framework\Plugin\KernelPluginLoader\DbalKernelPluginLoader;
use Shopware\Core\Framework\Routing\RequestTransformerInterface;
use Shopware\Development\Kernel;
use Shopware\Storefront\Framework\Cache\CacheStore;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;

if (PHP_VERSION_ID < 70200) {
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Error</h2>';
    echo 'Your server is running PHP version ' . PHP_VERSION . ' but Shopware 6 requires at least PHP 7.2.0';
    exit();
}

$classLoader = require __DIR__.'/../vendor/autoload.php';

// The check is to ensure we don't use .env if APP_ENV is defined
if (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv(true))->load(__DIR__.'/../.env');
}

$appEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ('prod' !== $appEnv));

if ($debug) {
    umask(0000);

    Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST);
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
    Request::setTrustedHosts(explode(',', $trustedHosts));
}

if (class_exists('Shopware\Core\HttpKernel')) {
    $request = Request::createFromGlobals();

    $kernel = new \Shopware\Development\HttpKernel($appEnv, $debug, $classLoader);
    $result = $kernel->handle($request);

    $result->getResponse()->send();

    $kernel->terminate($result->getRequest(), $result->getResponse());

    return;
}

// resolve SEO urls
$request = Request::createFromGlobals();
$connection = Kernel::getConnection();

if ($appEnv === 'dev') {
    $connection->getConfiguration()->setSQLLogger(
        new \Shopware\Core\Profiling\Doctrine\DebugStack()
    );
}

try {
    $shopwareVersion = Versions::getVersion('shopware/platform');

    $pluginLoader = new DbalKernelPluginLoader($classLoader, null, $connection);

    $cacheId = (new CacheIdLoader($connection))
        ->load();

    $kernel = new Kernel($appEnv, $debug, $pluginLoader, $cacheId, $shopwareVersion, $connection);
    $kernel->boot();

    $container = $kernel->getContainer();

    // resolves seo urls and detects storefront sales channels
    $request = $container
        ->get(RequestTransformerInterface::class)
        ->transform($request);

    $enabled = $container->getParameter('shopware.http.cache.enabled');
    if ($enabled) {
        $store = $container->get(CacheStore::class);

        $kernel = new HttpCache($kernel, $store, null, ['debug' => $debug]);
    }

    $response = $kernel->handle($request);

    $event = new BeforeSendResponseEvent($request, $response);
    $container->get('event_dispatcher')
        ->dispatch($event);

    $response = $event->getResponse();

} catch (DBALException $e) {
    $message = str_replace([$connection->getParams()['password'], $connection->getParams()['user']], '******', $e->getMessage());

    throw new RuntimeException(sprintf('Could not connect to database. Message from SQL Server: %s', $message));
}

$response->send();
$kernel->terminate($request, $response);
