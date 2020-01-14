<?php declare(strict_types=1);

namespace Shopware\Development\Analyze\PHPStan\Rules;

use PHPStan\Reflection\ClassReflection;

class AnnotationBasedRuleHelper
{
    public const DECORATABLE_ANNOTATION = 'Decoratable';

    public static function isClassTaggedWithAnnotation(ClassReflection $class, string $annotation): bool
    {
        $reflection = $class->getNativeReflection();

        return $reflection->getDocComment() && strpos($reflection->getDocComment(), '@' . $annotation) !== false;
    }
}
