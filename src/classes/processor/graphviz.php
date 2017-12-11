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

    public function __construct(plNodeLabelBuilder $labelBuilder = null)
    {
        $this->options = new plGraphvizProcessorOptions();
        $this->labelBuilder = $labelBuilder ??  new plNodeLabelBuilder(new TemplateEngine(
            new FileSystem(__DIR__ . '/../processor/graphviz/digraph/templates')
        ), new plGraphvizProcessorDefaultStyle());
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
        $def = '';

        $associations = [];
        /** @var plPhpAttribute $attribute */
        foreach ($class->attributes as $attribute) {
            // Association creation is optional
            if ($this->options->createAssociations === false) {
                continue;
            }

            // Create associations if the attribute type is set
            if ($attribute->hasType() && $this->isTypeInStructure($attribute->type) && !$this->isAssociationResolved($attribute->type, $associations)) {
                $def .= plEdge::association($this->structure[$attribute->type], $class)->toDotLanguage();
                $associations[strtolower($attribute->type)] = true;
            }
        }

        /** @var plPhpFunction $function */
        foreach ($class->functions as $function) {
            // Association creation is optional
            if ($this->options->createAssociations === false) {
                continue;
            }

            // Create association if the function is the constructor and takes
            // other classes as parameters
            if ($function->isConstructor()) {
                /** @var plPhpVariable $param */
                foreach ($function->params as $param) {
                    if ($param->hasType() && $this->isTypeInStructure($param->type) && !$this->isAssociationResolved($param->type, $associations)) {
                        $def .= plEdge::association($this->structure[$param->type], $class)->toDotLanguage();
                        $associations[strtolower($param->type)] = true;
                    }
                }
            }
        }

        // Create the node
        $def .= $this->createNode(
            "\"{$class->identifier()}\"",
            [
                'label' => $this->labelBuilder->labelForClass($class),
                'shape' => 'plaintext',
            ]
        );

        // Create class inheritance relation
        if ($class->hasParent()) {
            // Check if we need an "external" class node
            if (!$this->isTypeInStructure($class->extends->name)) {
                $def .= $this->getClassDefinition($class->extends);
            }
            $def .= plEdge::inheritance($class->extends, $class)->toDotLanguage();
        }

        // Create class implements relation
        foreach ($class->implements as $interface) {
            // Check if we need an "external" interface node
            if (!$this->isTypeInStructure($interface)) {
                $def .= $this->getInterfaceDefinition($interface);
            }
            $def .= plEdge::implementation($interface, $class)->toDotLanguage();
        }

        return $def;
    }

    private function getInterfaceDefinition(plPhpInterface $interface)
    {
        $def = '';

        $functions = [];
        foreach ($interface->functions as $function) {
            $functions[] = (string)$function;
        }

        // Create the node
        $def .= $this->createNode(
            $interface->identifier(),
            [
                'label' => $this->labelBuilder->labelForInterface($interface),
                'shape' => 'plaintext',
            ]
        );

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

    private function createNode($name, $options)
    {
        $node = $name . " [";
        foreach ($options as $key => $value) {
            $node .= $key . '=' . $value . ' ';
        }
        $node .= "]\n";
        return $node;
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
