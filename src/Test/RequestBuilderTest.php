<?php declare(strict_types=1);

namespace Shopware\Development\Test;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Api\Util\AccessKeyHelper;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Struct\Uuid;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\PlatformRequest;
use Shopware\Development\RequestTransformer;
use Shopware\Storefront\StorefrontRequest;
use Symfony\Component\HttpFoundation\Request;

class RequestBuilderTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var RequestTransformer
     */
    private $requestBuilder;

    protected function setUp()
    {
        $this->requestBuilder = new RequestTransformer($this->getContainer()->get(Connection::class));
    }

    /**
     * @dataProvider domainProvider
     *
     * @param array[]           $salesChannels
     * @param ExpectedRequest[] $requests
     */
    public function testDomainResolving(array $salesChannels, array $requests): void
    {
        $this->createSalesChannels($salesChannels);

        /** @var ExpectedRequest $expectedRequest */
        foreach ($requests as $expectedRequest) {
            $request = Request::create($expectedRequest->url);

            $resolved = $this->requestBuilder->transform($request);

            static::assertSame($expectedRequest->salesChannelId, $resolved->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID));
            static::assertSame($expectedRequest->isStorefrontRequest, $resolved->attributes->get(StorefrontRequest::ATTRIBUTE_IS_STOREFRONT_REQUEST));
            static::assertSame($expectedRequest->locale, $resolved->attributes->get(StorefrontRequest::ATTRIBUTE_DOMAIN_LOCALE));
            static::assertSame($expectedRequest->currency, $resolved->attributes->get(StorefrontRequest::ATTRIBUTE_DOMAIN_CURRENCY_ID));
            static::assertSame($expectedRequest->snippetSetId, $resolved->attributes->get(StorefrontRequest::ATTRIBUTE_DOMAIN_SNIPPET_SET_ID));
            static::assertSame($expectedRequest->language, $resolved->headers->get(PlatformRequest::HEADER_LANGUAGE_ID));
        }
    }

    public function domainProvider(): array
    {
        $germanId = Uuid::uuid4()->getHex();
        $englishId = Uuid::uuid4()->getHex();
        $gerUkId = Uuid::uuid4()->getHex();

        return [
            'single' => [
                [$this->getGermanSalesChannel($germanId, 'http://german.test')],
                [
                    new ExpectedRequest('http://german.test', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/foobar', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                ],
            ],
            'two' => [
                [
                    $this->getGermanSalesChannel($germanId, 'http://german.test'),
                    $this->getEnglishSalesChannel($englishId, 'http://english.test'),
                ],
                [
                    new ExpectedRequest('http://german.test', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/foobar', $germanId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),

                    new ExpectedRequest('http://english.test', $englishId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://english.test/', $englishId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://english.test/foobar', $englishId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                ],
            ],
            'single-with-ger-and-uk-domain' => [
                [
                    $this->getSalesChannelWithGerAndUkDomain($gerUkId, 'http://german.test', 'http://english.test'),
                ],
                [
                    new ExpectedRequest('http://german.test', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://german.test/foobar', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),

                    new ExpectedRequest('http://english.test', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://english.test/', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://english.test/foobar', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                ],
            ],
            'two-domains-same-host-different-path' => [
                [
                    $this->getSalesChannelWithGerAndUkDomain($gerUkId, 'http://saleschannel.test/de', 'http://saleschannel.test/en'),
                ],
                [
                    new ExpectedRequest('http://saleschannel.test/de', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://saleschannel.test/de/', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://saleschannel.test/de/foobar', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),

                    new ExpectedRequest('http://saleschannel.test/en', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://saleschannel.test/en/', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://saleschannel.test/en/foobar', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                ],
            ],
            'two-domains-same-host-extended-path' => [
                [
                    $this->getSalesChannelWithGerAndUkDomain($gerUkId, 'http://saleschannel.test/de', 'http://saleschannel.test'),
                ],
                [
                    new ExpectedRequest('http://saleschannel.test/de', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://saleschannel.test/de/', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),
                    new ExpectedRequest('http://saleschannel.test/de/foobar', $gerUkId, true, Defaults::LOCALE_DE_DE_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_DE, Defaults::SNIPPET_BASE_SET_DE),

                    new ExpectedRequest('http://saleschannel.test', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://saleschannel.test/', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                    new ExpectedRequest('http://saleschannel.test/foobar', $gerUkId, true, Defaults::LOCALE_EN_GB_ISO, Defaults::CURRENCY, Defaults::LANGUAGE_SYSTEM, Defaults::SNIPPET_BASE_SET_EN),
                ],
            ],
            'inactive' => [
                [
                    $this->getInactiveSalesChannel($germanId, 'http://inactive.test'),
                ],
                [
                    new ExpectedRequest('http://inactive.test', null, null, null, null, null, null),
                    new ExpectedRequest('http://inactive.test/', null, null, null, null, null, null),
                    new ExpectedRequest('http://inactive.test/foobar', null, null, null, null, null, null),
                ],
            ],
        ];
    }

    private function getEnglishSalesChannel($id, $url): array
    {
        return [
            'id' => $id,
            'name' => 'english',
            'languages' => [
                ['id' => Defaults::LANGUAGE_SYSTEM],
            ],
            'domains' => [
                [
                    'url' => $url,
                    'languageId' => Defaults::LANGUAGE_SYSTEM,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                ],
            ],
        ];
    }

    private function getGermanSalesChannel($id, $url): array
    {
        return [
            'id' => $id,
            'name' => 'german',
            'languages' => [
                ['id' => Defaults::LANGUAGE_DE],
            ],
            'domains' => [
                [
                    'url' => $url,
                    'languageId' => Defaults::LANGUAGE_DE,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                ],
            ],
        ];
    }

    private function getSalesChannelWithGerAndUkDomain($id, $gerUrl, $ukUrl): array
    {
        return [
            'id' => $id,
            'name' => 'english',
            'languages' => [
                ['id' => Defaults::LANGUAGE_SYSTEM],
                ['id' => Defaults::LANGUAGE_DE],
            ],
            'domains' => [
                [
                    'url' => $gerUrl,
                    'languageId' => Defaults::LANGUAGE_DE,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                ],
                [
                    'url' => $ukUrl,
                    'languageId' => Defaults::LANGUAGE_SYSTEM,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => Defaults::SNIPPET_BASE_SET_EN,
                ],
            ],
        ];
    }

    private function getInactiveSalesChannel($id, $url): array
    {
        return [
            'id' => $id,
            'name' => 'inactive sales channel',
            'active' => false,
            'languages' => [
                ['id' => Defaults::LANGUAGE_DE],
            ],
            'domains' => [
                [
                    'url' => $url,
                    'languageId' => Defaults::LANGUAGE_DE,
                    'currencyId' => Defaults::CURRENCY,
                    'snippetSetId' => Defaults::SNIPPET_BASE_SET_DE,
                ],
            ],
        ];
    }

    private function createSalesChannels($salesChannels): EntityWrittenContainerEvent
    {
        $salesChannels = array_map(function ($salesChannelData) {
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

class ExpectedRequest
{
    /** @var string */
    public $url;

    /** @var string */
    public $salesChannelId;

    /** @var bool */
    public $isStorefrontRequest;

    /** @var string */
    public $locale;

    /** @var string */
    public $currency;

    /** @var string */
    public $language;

    /** @var string */
    public $snippetSetId;

    public function __construct(
        string $url,
        ?string $salesChannelId,
        ?bool $isStorefrontRequest,
        ?string $locale,
        ?string $currency,
        ?string $language,
        ?string $snippetSetId
    ) {
        $this->url = $url;
        $this->salesChannelId = $salesChannelId;
        $this->isStorefrontRequest = $isStorefrontRequest;
        $this->locale = $locale;
        $this->currency = $currency;
        $this->language = $language;
        $this->snippetSetId = $snippetSetId;
    }
}
