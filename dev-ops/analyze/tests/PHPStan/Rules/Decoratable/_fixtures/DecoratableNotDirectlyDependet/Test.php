<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\Test\PHPStan\Rules\Decoratable\_fixtures\DecoratableNotDirectlyDependet;

class Test
{
    /**
     * @var DecoratableClass
     */
    private $decoratable;

    /**
     * @var NotTaggedClass
     */
    private $notTagged;

    public function __construct(DecoratableClass $decoratable, NotTaggedClass $notTagged)
    {
        $this->decoratable = $decoratable;
        $this->notTagged = $notTagged;
    }

    public function getDecoratable(): DecoratableClass
    {
        return $this->decoratable;
    }

    public function getNotTagged(): NotTaggedClass
    {
        return $this->notTagged;
    }
}
