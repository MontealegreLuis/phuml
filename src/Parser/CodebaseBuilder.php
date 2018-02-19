<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Name;
use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It builds a `Codebase` from `RawDefinitions`
 */
class CodebaseBuilder
{
    /** @var Codebase */
    private $codebase;

    public function __construct(Codebase $codebase = null)
    {
        $this->codebase = $codebase ?? new Codebase();
    }

    public function buildFrom(RawDefinitions $definitions): Codebase
    {
        foreach ($definitions->all() as $definition) {
            if ($this->codebase->has($definition->name())) {
                continue;
            }
            if ($definition->isClass()) {
                $this->codebase->add($this->buildClass($definitions, $definition));
            } elseif ($definition->isInterface()) {
                $this->codebase->add($this->buildInterface($definitions, $definition));
            }
        }
        return $this->codebase;
    }

    protected function buildInterface(RawDefinitions $definitions, RawDefinition $interface): InterfaceDefinition
    {
        $this->buildInterfaces($definitions, $interface->parents());
        return new InterfaceDefinition(
            $interface->name(),
            $interface->constants(),
            $interface->methods(),
            array_map(function (string $interface) { return Name::from($interface); }, $interface->parents())
        );
    }

    protected function buildClass(RawDefinitions $definitions, RawDefinition $class): ClassDefinition
    {
        $this->resolveParentClass($definitions, $class->parent());
        $this->buildInterfaces($definitions, $class->interfaces());
        return new ClassDefinition(
            $class->name(),
            $class->constants(),
            $class->methods(),
            $class->hasParent() ? Name::from($class->parent()) : null,
            $class->attributes(),
            array_map(function (string $interface) { return Name::from($interface); }, $class->interfaces())
        );
    }

    /**
     * @param string[] $implements
     * @return Definition[]
     */
    protected function buildInterfaces(RawDefinitions $definitions, array $implements): array
    {
        return array_map(function (string $interface) use ($definitions) {
            return $this->resolveRelatedInterface($definitions, $interface);
        }, $implements);
    }

    protected function resolveRelatedInterface(RawDefinitions $definitions, string $interface): Definition
    {
        if (!$this->codebase->has($interface)) {
            $this->codebase->add($this->buildInterface(
                $definitions,
                $definitions->get($interface)
            ));
        }
        return $this->codebase->get($interface);
    }

    /**
     * It adds the parent definition to the codebase if has not been added yet
     */
    protected function resolveParentClass(RawDefinitions $definitions, ?string $parent): void
    {
        if ($parent === null) {
            return;
        }
        if (!$this->codebase->has($parent)) {
            $this->codebase->add($this->buildClass($definitions, $definitions->get($parent)));
        }
    }
}
