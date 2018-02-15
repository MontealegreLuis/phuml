<?php
/**
 * PHP version 7.1
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
class Edge implements HasDotRepresentation
{
    /** @var HasNodeIdentifier */
    private $fromNode;

    /** @var HasNodeIdentifier */
    private $toNode;

    /** @var string */
    private $options;

    public function __construct(
        HasNodeIdentifier $nodeA,
        HasNodeIdentifier $nodeB,
        string $options
    )
    {
        $this->fromNode = $nodeA;
        $this->toNode = $nodeB;
        $this->options = $options;
    }

    public static function inheritance(HasNodeIdentifier $parent, HasNodeIdentifier $child): Edge
    {
        return new Edge($parent, $child, 'dir=back arrowtail=empty style=solid');
    }

    public static function implementation(HasNodeIdentifier $interface, HasNodeIdentifier $class): Edge
    {
        return new Edge($interface, $class, 'dir=back arrowtail=normal style=dashed');
    }

    public static function association(HasNodeIdentifier $reference, HasNodeIdentifier $class): Edge
    {
        return new Edge($reference, $class, 'dir=back arrowtail=none style=solid');
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
