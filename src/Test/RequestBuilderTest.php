<?php

namespace Shopware\Development\Test;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Util\AccessKeyHelper;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Struct\Uuid;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Development\RequestBuilder;
use Symfony\Component\HttpFoundation\Request;

class RequestBuilderTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var RequestBuilder
     */
    private $requestBuilder;

    protected function setUp()
    {
        $this->requestBuilder = new RequestBuilder($this->getContainer()->get(Connection::class));
    }

    public function testSingleSalesChannel(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'a',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannel.test',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'saleschannel.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannel.test';
        $path = '/';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannel.test';
        $path = '/foobar';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );
    }

    public function testTwoSalesChannels(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'a',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannela.test',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ],
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'b',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannelb.test',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'saleschannela.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannela.test';
        $path = '/foobar';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannelb.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[1]['domains'][0]['url'],
            $salesChannels[1]['domains'][0]['languageId'],
            $salesChannels[1]['domains'][0]['currencyId'],
            $salesChannels[1]['domains'][0]['snippetSetId'],
            $salesChannels[1]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannelb.test';
        $path = '/foobar';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[1]['domains'][0]['url'],
            $salesChannels[1]['domains'][0]['languageId'],
            $salesChannels[1]['domains'][0]['currencyId'],
            $salesChannels[1]['domains'][0]['snippetSetId'],
            $salesChannels[1]['domains'][0]['localeCode'],
            $url
        );
    }

    public function testSalesChannelTwoDomains(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'new sales channel',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://en.saleschannel.test',
                        'languageId' => Defaults::LANGUAGE_SYSTEM,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                        'localeCode' => Defaults::LOCALE_EN_GB_ISO
                    ],
                    [
                        'url' => 'http://de.saleschannel.test',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'en.saleschannel.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'de.saleschannel.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][1]['url'],
            $salesChannels[0]['domains'][1]['languageId'],
            $salesChannels[0]['domains'][1]['currencyId'],
            $salesChannels[0]['domains'][1]['snippetSetId'],
            $salesChannels[0]['domains'][1]['localeCode'],
            $url
        );
    }

    public function testSalesChannelSameHostDifferentPaths(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'new sales channel',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannel.test/en',
                        'languageId' => Defaults::LANGUAGE_SYSTEM,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                        'localeCode' => Defaults::LOCALE_EN_GB_ISO
                    ],
                    [
                        'url' => 'http://saleschannel.test/de',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'saleschannel.test';
        $path = '/en';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannel.test';
        $path = '/de';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][1]['url'],
            $salesChannels[0]['domains'][1]['languageId'],
            $salesChannels[0]['domains'][1]['currencyId'],
            $salesChannels[0]['domains'][1]['snippetSetId'],
            $salesChannels[0]['domains'][1]['localeCode'],
            $url
        );
    }

    public function testSalesChannelExtendedPath(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'new sales channel',
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannel.test',
                        'languageId' => Defaults::LANGUAGE_SYSTEM,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                        'localeCode' => Defaults::LOCALE_EN_GB_ISO
                    ],
                    [
                        'url' => 'http://saleschannel.test/de',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'saleschannel.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][0]['url'],
            $salesChannels[0]['domains'][0]['languageId'],
            $salesChannels[0]['domains'][0]['currencyId'],
            $salesChannels[0]['domains'][0]['snippetSetId'],
            $salesChannels[0]['domains'][0]['localeCode'],
            $url
        );

        $host = 'saleschannel.test';
        $path = '/de';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        $this->assertResolvedUrl(
            $salesChannels[0]['domains'][1]['url'],
            $salesChannels[0]['domains'][1]['languageId'],
            $salesChannels[0]['domains'][1]['currencyId'],
            $salesChannels[0]['domains'][1]['snippetSetId'],
            $salesChannels[0]['domains'][1]['localeCode'],
            $url
        );
    }

    public function testInactiveNoResult(): void
    {
        $salesChannels = [
            [
                'id' => Uuid::uuid4()->getHex(),
                'name' => 'new sales channel',
                'active' => false,
                'languages' => [
                    ['id' => Defaults::LANGUAGE_DE]
                ],
                'domains' => [
                    [
                        'url' => 'http://saleschannel.test',
                        'languageId' => Defaults::LANGUAGE_DE,
                        'currencyId' => Defaults::CURRENCY,
                        'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                        'localeCode' => Defaults::LOCALE_DE_DE_ISO
                    ]
                ]
            ]
        ];
        $this->createSalesChannels($salesChannels);

        $host = 'saleschannel.test';
        $path = '';
        $server['HTTP_HOST'] = $host;
        $request = Request::create($path, 'GET', [], [], [], $server);

        $url = $this->requestBuilder->findSalesChannel($request);
        static::assertNull($url);
    }

    protected function assertResolvedUrl(
        string $expectedUrl,
        string $expectedLanguageId,
        string $expectedCurrency,
        string $expectedSnippetSetId,
        string $expectedLocaleCode,
        array $actualUrl
    ): void {
        static::assertNotEmpty($actualUrl);
        static::assertArrayHasKey('url', $actualUrl);
        static::assertArrayHasKey('languageId', $actualUrl);
        static::assertArrayHasKey('salesChannelId', $actualUrl);
        static::assertArrayHasKey('currencyId', $actualUrl);
        static::assertArrayHasKey('snippetSetId', $actualUrl);
        static::assertArrayHasKey('localeCode', $actualUrl);

        static::assertEquals($expectedUrl, $actualUrl['url']);
        static::assertEquals($expectedLanguageId, $actualUrl['languageId']);
        static::assertEquals($expectedCurrency, $actualUrl['currencyId']);
        static::assertEquals($expectedSnippetSetId, $actualUrl['snippetSetId']);
        static::assertEquals($expectedLocaleCode, $actualUrl['localeCode']);
    }

    private function createSalesChannels($salesChannels): EntityWrittenContainerEvent
    {
        $salesChannels = array_map(function($salesChannelData) {
            $defaults = [
                'typeId' => Defaults::SALES_CHANNEL_STOREFRONT,
                'accessKey' => AccessKeyHelper::generateAccessKey('sales-channel'),
                'languageId' => Defaults::LANGUAGE_SYSTEM,
                'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                'currencyId' => Defaults::CURRENCY,
                'currencyVersionId' => Defaults::LIVE_VERSION,
                'paymentMethodId' => Defaults::PAYMENT_METHOD_DEBIT,
                'paymentMethodVersionId' => Defaults::LIVE_VERSION,
                'shippingMethodId' => Defaults::SHIPPING_METHOD,
                'shippingMethodVersionId' => Defaults::LIVE_VERSION,
                'countryId' => Defaults::COUNTRY,
                'countryVersionId' => Defaults::LIVE_VERSION,
                'catalogs' => [['id' => Defaults::CATALOG]],
                'currencies' => [['id' => Defaults::CURRENCY]],
                'languages' => [['id' => Defaults::LANGUAGE_SYSTEM]],
                'paymentMethods' => [['id' => Defaults::PAYMENT_METHOD_DEBIT]],
                'shippingMethods' => [['id' => Defaults::SHIPPING_METHOD]],
                'countries' => [['id' => Defaults::COUNTRY]],
            ];
            return array_merge_recursive($defaults, $salesChannelData);
        }, $salesChannels);

        $salesChannelRepository = $this->getContainer()->get('sales_channel.repository');
        return $salesChannelRepository->create($salesChannels, Context::createDefaultContext());
    }
}