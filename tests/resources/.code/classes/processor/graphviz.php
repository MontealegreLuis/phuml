<?php
use phuml\interfaces\plProcessor;

class plGraphvizProcessor extends plProcessor
{
    private $properties;

    private $output;

    private $structure;

    /** @var plProcessorOptions */
    public $options;

    public function __construct()
    {
    }

    public function getInputTypes()
    {
    }

    public function getOutputType()
    {
    }

    public function process( $input, $type )
    {
    }

    private function getClassDefinition( $o )
    {
    }

    private function getInterfaceDefinition( $o )
    {
    }

    private function getModifierRepresentation( $modifier )
    {
    }

    private function getParamRepresentation( $params )
    {
    }

    private function getUniqueId( $object )
    {
    }

    private function createNode( $name, $options )
    {
    }

    private function createNodeRelation( $node1, $node2, $options )
    {
    }

    private function createInterfaceLabel( $name, $attributes, $functions )
    {
    }

    private function createClassLabel( $name, $attributes, $functions )
    {
    }
}
