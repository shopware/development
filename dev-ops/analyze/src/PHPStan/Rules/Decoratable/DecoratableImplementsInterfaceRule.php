<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\PHPStan\Rules\Decoratable;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use Shopware\Development\Analyze\PHPStan\Rules\AnnotationBasedRuleHelper;

class DecoratableImplementsInterfaceRule implements Rule
{
    /**
     * @var Broker
     */
    private $broker;

    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }

    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!isset($node->namespacedName)) {
            // skip anonymous classes
            return [];
        }

        $class = $this->broker->getClass($scope->resolveName($node->namespacedName));
        if (!AnnotationBasedRuleHelper::isClassTaggedWithAnnotation($class, AnnotationBasedRuleHelper::DECORATABLE_ANNOTATION)) {
            return [];
        }

        if ($this->implementsInterface($class)) {
            return [];
        }

        return [
            sprintf(
                'The service "%s" is marked as "@Decoratable", but does not implement an interface.',
                $class->getName()
            ),
        ];
    }

    private function implementsInterface(ClassReflection $class): bool
    {
        if (!empty($class->getInterfaces())) {
            return true;
        }

        $parentClass = $class->getParentClass();
        if ($parentClass) {
            return $this->implementsInterface($parentClass);
        }

        return false;
    }
}
