<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Shopware\Development\Analyze\PHPStan\Rules\Decoratable\DecoratableImplementsInterfaceRule;

class DecoratableImplementsInterfaceRuleTest extends RuleTestCase
{
    public function testDecoratableImplementsInterface(): void
    {
        $this->analyse([
            __DIR__ . '/_fixtures/DecoratableImplementsInterface/DecoratableDoesImplementInterface.php',
            __DIR__ . '/_fixtures/DecoratableImplementsInterface/DecoratableDoesNotImplementInterface.php',
        ], [
            [
                'The service "Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable\_fixtures\DecoratableImplementsInterface\DecoratableDoesNotImplementInterface" is marked as "@Decoratable", but does not implement an interface.',
                10,
            ],
        ]);
    }

    public function testNotTaggedClassIsAllowedToNotImplementInterface(): void
    {
        $this->analyse([
            __DIR__ . '/_fixtures/DecoratableImplementsInterface/NotTaggedClassIsAllowedToNotImplemetInterface.php',
        ], []);
    }

    protected function getRule(): Rule
    {
        return new DecoratableImplementsInterfaceRule($this->createBroker());
    }
}
