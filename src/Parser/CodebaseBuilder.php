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
                $this->codebase->addClass($this->buildClass($definitions, $definition));
            } elseif ($definition->isInterface()) {
                $this->codebase->addInterface($this->buildInterface($definitions, $definition));
            }
        }
        return $this->codebase;
    }

    protected function buildInterface(RawDefinitions $definitions, RawDefinition $interface): InterfaceDefinition
    {
        return new InterfaceDefinition(
            $interface->name(),
            $interface->constants(),
            $interface->methods(),
            $this->buildInterfaces($definitions, $interface->parents())
        );
    }

    protected function buildClass(RawDefinitions $definitions, RawDefinition $class): ClassDefinition
    {
        return new ClassDefinition(
            $class->name(),
            $class->constants(),
            $class->methods(),
            $this->resolveParentClass($definitions, $class->parent()),
            $class->attributes(),
            $this->buildInterfaces($definitions, $class->interfaces())
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
            $this->codebase->addInterface($this->buildInterface(
                $definitions,
                $definitions->get($interface)
            ));
        }
        return $this->codebase->get($interface);
    }

    protected function resolveParentClass(RawDefinitions $definitions, ?string $parent): ?Definition
    {
        if ($parent === null) {
            return null;
        }
        if (!$this->codebase->has($parent)) {
            $this->codebase->addClass($this->buildClass($definitions, $definitions->get($parent)));
        }
        return $this->codebase->get($parent);
    }
}
