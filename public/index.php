<?php declare(strict_types=1);

use Shopware\Development\HttpKernel;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

if (\PHP_VERSION_ID < 70400) {
    header('Content-type: text/html; charset=utf-8', true, 503);

    echo '<h2>Error</h2>';
    echo 'Your server is running PHP version ' . \PHP_VERSION . ' but Shopware 6 requires at least PHP 7.4.0';
    exit(1);
}

$classLoader = require __DIR__ . '/../vendor/autoload.php';

// The check is to ensure we don't use .env if APP_ENV is defined
if (!isset($_SERVER['APP_ENV']) && !isset($_ENV['APP_ENV'])) {
    if (!class_exists(Dotenv::class)) {
        throw new \RuntimeException('APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.');
    }
    (new Dotenv())->usePutenv()->load(__DIR__ . '/../.env');
}

$appEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'dev';
$debug = (bool) ($_SERVER['APP_DEBUG'] ?? $_ENV['APP_DEBUG'] ?? ($appEnv !== 'prod'));

if ($debug) {
    umask(0000);

    Debug::enable();
}

$trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false;
if ($trustedProxies) {
    Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO);
}

$trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false;
if ($trustedHosts) {
    Request::setTrustedHosts(explode(',', $trustedHosts));
}

$request = Request::createFromGlobals();

$kernel = new HttpKernel($appEnv, $debug, $classLoader);

if ($_SERVER['COMPOSER_PLUGIN_LOADER'] ?? $_SERVER['DISABLE_EXTENSIONS'] ?? false) {
    $kernel->setPluginLoader(new \Shopware\Core\Framework\Plugin\KernelPluginLoader\ComposerPluginLoader($classLoader));
}

$result = $kernel->handle($request);

$result->getResponse()->send();

$kernel->terminate($result->getRequest(), $result->getResponse());
