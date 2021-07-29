<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * An edge represents 1 of 3 types of relationships between classes and interfaces
 *
 * 1. Inheritance
 * 2. Interface implementation
 * 3. Associations
 *      - Via constructor injection
 *      - Via class attributes
 */
final class Edge implements HasDotRepresentation
{
    public static function inheritance(HasNodeIdentifier $parent, HasNodeIdentifier $child): Edge
    {
        return new Edge($parent, $child, 'dir=back arrowtail=empty style=solid');
    }

    public static function implementation(HasNodeIdentifier $interface, HasNodeIdentifier $class): Edge
    {
        return new Edge($interface, $class, 'dir=back arrowtail=empty style=dashed');
    }

    public static function association(HasNodeIdentifier $reference, HasNodeIdentifier $class): Edge
    {
        return new Edge($reference, $class, 'dir=back arrowtail=none style=solid');
    }

    /**
     * A trait can be used by another trait or a class
     */
    public static function use(HasNodeIdentifier $trait, HasNodeIdentifier $definition): Edge
    {
        return new Edge($trait, $definition, 'dir=back arrowtail=normal style=solid');
    }

    private function __construct(
        private HasNodeIdentifier $fromNode,
        private HasNodeIdentifier $toNode,
        private string $options
    ) {
    }

    public function fromNode(): HasNodeIdentifier
    {
        return $this->fromNode;
    }

    public function toNode(): HasNodeIdentifier
    {
        return $this->toNode;
    }

    public function options(): string
    {
        return $this->options;
    }

    public function dotTemplate(): string
    {
        return 'edge';
    }
}
