<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plEdge implements plHasDotRepresentation
{
    /** @var plHasNodeIdentifier */
    private $fromNode;

    /** @var plHasNodeIdentifier */
    private $toNode;

    /** @var string */
    private $options;

    public static function inheritance(plHasNodeIdentifier $parent, plHasNodeIdentifier $child): plEdge
    {
        return new plEdge($parent, $child, 'dir=back arrowtail=empty style=solid');
    }

    public static function implementation(plHasNodeIdentifier $interface, plHasNodeIdentifier $class): plEdge
    {
        return new plEdge($interface, $class, 'dir=back arrowtail=normal style=dashed');
    }

    public static function association(plHasNodeIdentifier $reference, plHasNodeIdentifier $class): plEdge
    {
        return new plEdge($reference, $class, 'dir=back arrowtail=none style=dashed');
    }

    public function toDotLanguage(): string
    {
        return "\"{$this->fromNode->identifier()}\" -> \"{$this->toNode->identifier()}\" [{$this->options}]\n";
    }

    private function __construct(plHasNodeIdentifier $nodeA, plHasNodeIdentifier $nodeB, string $options)
    {
        $this->fromNode = $nodeA;
        $this->toNode = $nodeB;
        $this->options = $options;
    }
}
