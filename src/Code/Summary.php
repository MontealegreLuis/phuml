<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Code\Modifiers\Visibility;

/**
 * It creates a summary of the classes, interfaces, methods, and properties of a codebase
 *
 * The summary of a `Structure` does not include counts of constants
 */
final class Summary
{
    /** @noRector \Rector\Php81\Rector\Property\ReadOnlyPropertyRector  */
    private int $interfaceCount;

    /** @noRector \Rector\Php81\Rector\Property\ReadOnlyPropertyRector  */
    private int $classCount;

    private int $publicFunctionCount;

    private int $publicPropertyCount;

    private int $publicTypedProperties;

    private int $protectedFunctionCount;

    private int $protectedPropertyCount;

    private int $protectedTypedProperties;

    private int $privateFunctionCount;

    private int $privatePropertyCount;

    private int $privateTypedProperties;

    public static function from(Codebase $codebase): Summary
    {
        return new Summary($codebase);
    }

    private function __construct(Codebase $codebase)
    {
        $this->interfaceCount = 0;
        $this->classCount = 0;
        $this->publicFunctionCount = 0;
        $this->publicPropertyCount = 0;
        $this->publicTypedProperties = 0;
        $this->protectedFunctionCount = 0;
        $this->protectedPropertyCount = 0;
        $this->protectedTypedProperties = 0;
        $this->privateFunctionCount = 0;
        $this->privatePropertyCount = 0;
        $this->privateTypedProperties = 0;
        foreach ($codebase->definitions() as $definition) {
            if ($definition instanceof InterfaceDefinition) {
                $this->interfaceCount++;
            }
            if ($definition instanceof ClassDefinition) {
                $this->classCount++;
                $this->propertiesSummary($definition);
            }
            $this->methodsSummary($definition);
        }
    }

    private function propertiesSummary(ClassDefinition $definition): void
    {
        // Properties count
        $this->publicPropertyCount += $definition->countPropertiesByVisibility(Visibility::PUBLIC);
        $this->protectedPropertyCount += $definition->countPropertiesByVisibility(Visibility::PROTECTED);
        $this->privatePropertyCount += $definition->countPropertiesByVisibility(Visibility::PRIVATE);

        // Typed properties count
        $this->publicTypedProperties += $definition->countTypedPropertiesByVisibility(Visibility::PUBLIC);
        $this->protectedTypedProperties += $definition->countTypedPropertiesByVisibility(Visibility::PROTECTED);
        $this->privateTypedProperties += $definition->countTypedPropertiesByVisibility(Visibility::PRIVATE);
    }

    private function methodsSummary(Definition $definition): void
    {
        $this->publicFunctionCount += $definition->countMethodsByVisibility(Visibility::PUBLIC);
        $this->protectedFunctionCount += $definition->countMethodsByVisibility(Visibility::PROTECTED);
        $this->privateFunctionCount += $definition->countMethodsByVisibility(Visibility::PRIVATE);
    }

    public function interfaceCount(): int
    {
        return $this->interfaceCount;
    }

    public function classCount(): int
    {
        return $this->classCount;
    }

    public function publicFunctionCount(): int
    {
        return $this->publicFunctionCount;
    }

    public function publicPropertyCount(): int
    {
        return $this->publicPropertyCount;
    }

    public function publicTypedProperties(): int
    {
        return $this->publicTypedProperties;
    }

    public function protectedFunctionCount(): int
    {
        return $this->protectedFunctionCount;
    }

    public function protectedPropertyCount(): int
    {
        return $this->protectedPropertyCount;
    }

    public function protectedTypedProperties(): int
    {
        return $this->protectedTypedProperties;
    }

    public function privateFunctionCount(): int
    {
        return $this->privateFunctionCount;
    }

    public function privatePropertyCount(): int
    {
        return $this->privatePropertyCount;
    }

    public function privateTypedProperties(): int
    {
        return $this->privateTypedProperties;
    }

    public function functionCount(): int
    {
        return $this->publicFunctionCount + $this->protectedFunctionCount + $this->privateFunctionCount;
    }

    public function propertiesCount(): int
    {
        return $this->publicPropertyCount + $this->protectedPropertyCount + $this->privatePropertyCount;
    }

    public function typedPropertiesCount(): int
    {
        return $this->publicTypedProperties + $this->protectedTypedProperties + $this->privateTypedProperties;
    }

    public function propertiesPerClass(): float
    {
        if ($this->classCount === 0) {
            return 0;
        }

        return round($this->propertiesCount() / $this->classCount, 2);
    }

    public function functionsPerClass(): float
    {
        if ($this->classCount === 0) {
            return 0;
        }

        return round($this->functionCount() / $this->classCount, 2);
    }
}
