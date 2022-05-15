<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Graphviz\Edge;

/**
 * It creates directed edges by inspecting a class
 *
 * 1. It creates edges by inspecting the properties of a class
 * 2. It creates edges by inspecting the parameters of the constructor of a class
 */
final class DirectedEdgesBuilder implements EdgesBuilder
{
    /** @var bool[] */
    private array $edges = [];

    /**
     * It creates an edge if the property
     *
     * - Has type information, and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return Edge[]
     */
    public function fromProperties(ClassDefinition $class, Codebase $codebase): array
    {
        return $this->buildEdgesFor($class, $class->properties(), $codebase);
    }

    /**
     * It creates an edge if the constructor parameter
     *
     * - Has type information, and it's not a PHP's built-in type
     * - The association hasn't already been resolved
     *
     * @return Edge[]
     */
    public function fromConstructor(ClassDefinition $class, Codebase $codebase): array
    {
        return $this->buildEdgesFor($class, $class->constructorParameters(), $codebase);
    }

    /**
     * @param HasType[] $variables
     * @return Edge[]
     */
    private function buildEdgesFor(ClassDefinition $class, array $variables, Codebase $codebase): array
    {
        $edges = [];
        foreach ($variables as $variable) {
            $key = EdgeKey::from($class->name(), $variable->type());
            if ($this->isAssociationResolved($key)) {
                continue;
            }

            $this->markAssociationResolvedFor($key);
            $edges[] = $this->addAssociations($class, $variable, $codebase);
        }
        return array_merge(...$edges);
    }

    /** @return Edge[] */
    private function addAssociations(ClassDefinition $class, HasType $property, Codebase $codebase): array
    {
        return array_map(
            static fn (Name $reference): Edge => Edge::association($codebase->get($reference), $class),
            $property->references()
        );
    }

    private function isAssociationResolved(EdgeKey $key): bool
    {
        return array_key_exists((string) $key, $this->edges) && $this->edges[(string) $key] === true;
    }

    private function markAssociationResolvedFor(EdgeKey $key): void
    {
        $this->edges[(string) $key] = true;
    }
}
