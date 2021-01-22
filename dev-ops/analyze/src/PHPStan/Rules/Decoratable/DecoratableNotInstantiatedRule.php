<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\PHPStan\Rules\Decoratable;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PHPStan\Analyser\Scope;
use PHPStan\Broker\Broker;
use PHPStan\Rules\Rule;
use Shopware\Development\Analyze\PHPStan\Rules\AnnotationBasedRuleHelper;

class DecoratableNotInstantiatedRule implements Rule
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
        return Expr\New_::class;
    }

    /**
     * @param Expr\New_ $node
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->class instanceof Node\Name || !$node->class->isFullyQualified()) {
            return [];
        }
        $class = $this->broker->getClass($node->class->toString());

        if (!AnnotationBasedRuleHelper::isClassTaggedWithAnnotation($class, AnnotationBasedRuleHelper::DECORATABLE_ANNOTATION)) {
            return [];
        }

        return [
            sprintf(
                'The service "%s" is marked as "@Decoratable", but is instantiated, use constructor injection via the DIC instead.',
                $class->getName()
            ),
        ];
    }
}
