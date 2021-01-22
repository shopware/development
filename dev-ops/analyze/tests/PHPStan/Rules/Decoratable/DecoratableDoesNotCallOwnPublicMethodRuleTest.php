<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use Shopware\Development\Analyze\PHPStan\Rules\Decoratable\DecoratableDoesNotCallOwnPublicMethodRule;

class DecoratableDoesNotCallOwnPublicMethodRuleTest extends RuleTestCase
{
    public function testDecoratableDoesNotCallOwnPublicMethod(): void
    {
        $this->analyse([
            __DIR__ . '/_fixtures/DecoratableDoesNotCallOwnPublicMethod/DecoratableDoesCallOwnPublicMethod.php',
        ], [
            [
                'The service "Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable\_fixtures\DecoratableDoesNotCallOwnPublicMethod\DecoratableDoesCallOwnPublicMethod" is marked as "@Decoratable", but calls it\'s own public method "build", which breaks decoration.',
                14,
            ],
        ]);
    }

    public function testNotTaggedClassIsAllowedToCallOwnPublicMethod(): void
    {
        $this->analyse([
            __DIR__ . '/_fixtures/DecoratableDoesNotCallOwnPublicMethod/NotTaggedClassIsAllowedToCallOwnPublicMethod.php',
        ], []);
    }

    protected function getRule(): Rule
    {
        return new DecoratableDoesNotCallOwnPublicMethodRule();
    }
}
