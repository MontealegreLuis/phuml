<?php

use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class plGraphvizProcessor extends plProcessor
{
    private $output;

    private $structure;

    public $options;

    /** @var plClassGraphElements */
    private $classElements;

    /** @var plInterfaceGraphElements */
    private $interfaceElements;

    public function __construct(
        plClassGraphElements $classElements = null,
        plInterfaceGraphElements $interfaceElements = null
    ) {
        $this->options = new plGraphvizProcessorOptions();
        $labelBuilder =  new plNodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../processor/graphviz/digraph/templates')
        ), new plGraphvizProcessorDefaultStyle());
        $this->classElements = $classElements ?? new plClassGraphElements($this->options->createAssociations, $labelBuilder);
        $this->interfaceElements = $interfaceElements ?? new plInterfaceGraphElements($labelBuilder);
    }

    public function getInputTypes()
    {
        return [
            'application/phuml-structure'
        ];
    }

    public function getOutputType()
    {
        return 'text/dot';
    }

    public function process($input, $type)
    {
        $this->structure = $input;

        $this->output = 'digraph "' . sha1(mt_rand()) . '" {' . "\n";
        $this->output .= 'splines = true;' . "\n";
        $this->output .= 'overlap = false;' . "\n";
        $this->output .= 'mindist = 0.6;' . "\n";

        foreach ($this->structure as $object) {
            if ($object instanceof plPhpClass) {
                $this->output .= $this->getClassDefinition($object);
            } else if ($object instanceof plPhpInterface) {
                $this->output .= $this->getInterfaceDefinition($object);
            }
        }

        $this->output .= "}";

        return $this->output;
    }

    private function getClassDefinition(plPhpClass $class)
    {
        $dotElements = $this->classElements->extractFrom($class, $this->structure);

        $dotFormat = array_map(function (plHasDotRepresentation $element) {
            return $element->toDotLanguage();
        }, $dotElements);

        return implode('', $dotFormat);
    }

    private function getInterfaceDefinition(plPhpInterface $interface): string
    {
        $dotElements = $this->interfaceElements->extractFrom($interface);

        $dotFormat = array_map(function (plHasDotRepresentation $element) {
            return $element->toDotLanguage();
        }, $dotElements);

        return implode('', $dotFormat);
    }
}
