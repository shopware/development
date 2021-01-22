<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Shopware\Development\Analyze\PHPStan\Rules\Decoratable\DecoratableNotDirectlyDependetRule;

class DecoratableNotDirectlyDependetRuleTest extends RuleTestCase
{
    private const ERROR_MSG = 'The service "Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable\_fixtures\DecoratableNotDirectlyDependet\Test" has a direct dependency on decoratable service "Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable\_fixtures\DecoratableNotDirectlyDependet\DecoratableClass", but must only depend on it\'s interface.';

    public function testDecoratableImplementsImterface(): void
    {
        $this->analyse([
            __DIR__ . '/_fixtures/DecoratableNotDirectlyDependet/Test.php',
        ], [
            [
                self::ERROR_MSG,
                10,
            ],
            [
                self::ERROR_MSG,
                17,
            ],
            [
                self::ERROR_MSG,
                23,
            ],
        ]);
    }

    protected function getRule(): Rule
    {
        return new DecoratableNotDirectlyDependetRule($this->createBroker());
    }
}
