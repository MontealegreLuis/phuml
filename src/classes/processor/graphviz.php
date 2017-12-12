<?php

use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

class plGraphvizProcessor extends plProcessor
{
    private $output;

    private $structure;

    public $options;

    /** @var plNodeLabelBuilder */
    private $labelBuilder;

    /** @var plClassGraphElements */
    private $classElements;

    public function __construct(plNodeLabelBuilder $labelBuilder = null, plClassGraphElements $classElements = null)
    {
        $this->options = new plGraphvizProcessorOptions();
        $this->labelBuilder = $labelBuilder ??  new plNodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../processor/graphviz/digraph/templates')
        ), new plGraphvizProcessorDefaultStyle());
        $this->classElements = $classElements ?? new plClassGraphElements($this->options->createAssociations, $this->labelBuilder);
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

    private function getInterfaceDefinition(plPhpInterface $interface)
    {
        $def = '';

        $functions = [];
        foreach ($interface->functions as $function) {
            $functions[] = (string)$function;
        }

        $def .= (new plNode($interface, $this->labelBuilder->labelForInterface($interface)))->toDotLanguage();

        // Create interface inheritance relation
        if ($interface->hasParent()) {
            // Check if we need an "external" interface node
            if (!$this->isTypeInStructure($interface->extends)) {
                $def .= $this->getInterfaceDefinition($interface->extends);
            }
            $def .= plEdge::inheritance($interface->extends, $interface)->toDotLanguage();
        }

        return $def;
    }

    private function isTypeInStructure(string $type): bool
    {
        return array_key_exists($type, $this->structure);
    }

    /**
     * @param bool[] $associations
     */
    private function isAssociationResolved(string $type, array $associations): bool
    {
        return array_key_exists(strtolower($type), $associations);
    }
}
