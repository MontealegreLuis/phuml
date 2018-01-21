<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Definition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Structure;
use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\Raw\RawDefinitions;

/**
 * It builds a `Structure` from a `RawDefinitions`
 */
class StructureBuilder
{
    /** @var Structure */
    private $structure;

    /** @var DefinitionMembersBuilder */
    protected $builder;

    public function __construct(Structure $structure = null, DefinitionMembersBuilder $builder = null)
    {
        $this->structure = $structure ?? new Structure();
        $this->builder = $builder ?? new DefinitionMembersBuilder();
    }

    public function buildFrom(RawDefinitions $definitions): Structure
    {
        foreach ($definitions->all() as $definition) {
            if ($this->structure->has($definition->name())) {
                continue;
            }
            if ($definition->isClass()) {
                $this->structure->addClass($this->buildClass($definitions, $definition));
            } elseif ($definition->isInterface()) {
                $this->structure->addInterface($this->buildInterface($definitions, $definition));
            }
        }
        return $this->structure;
    }

    protected function buildInterface(RawDefinitions $definitions, RawDefinition $interface): InterfaceDefinition
    {
        return new InterfaceDefinition(
            $interface->name(),
            $this->builder->methods($interface),
            $this->resolveRelatedInterface($definitions, $interface->parent())
        );
    }

    protected function buildClass(RawDefinitions $definitions, RawDefinition $class): ClassDefinition
    {
        return new ClassDefinition(
            $class->name(),
            $this->builder->attributes($class),
            $this->builder->methods($class),
            $this->buildInterfaces($definitions, $class->interfaces()),
            $this->resolveParentClass($definitions, $class->parent())
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

    protected function resolveRelatedInterface(RawDefinitions $definitions, ?string $interface): ?Definition
    {
        if ($interface === null) {
            return null;
        }
        if (!$this->structure->has($interface)) {
            $this->structure->addInterface($this->buildInterface(
                $definitions,
                $definitions->get($interface)
            ));
        }
        return $this->structure->get($interface);
    }

    protected function resolveParentClass(RawDefinitions $definitions, ?string $parent): ?Definition
    {
        if ($parent === null) {
            return null;
        }
        if (!$this->structure->has($parent)) {
            $this->structure->addClass($this->buildClass($definitions, $definitions->get($parent)));
        }
        return $this->structure->get($parent);
    }
}
