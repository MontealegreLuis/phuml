<?php

class plGraphvizProcessor extends plProcessor
{
    private $output;

    private $structure;

    public $options;

    public function __construct()
    {
        $this->options = new plGraphvizProcessorOptions();
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

        // First we need to create the needed data arrays
        $name = $class->name;

        $attributes = [];
        $associations = [];
        /** @var plPhpAttribute $attribute */
        foreach ($class->attributes as $attribute) {
            $attributes[] = (string)$attribute;

            // Association creation is optional
            if ($this->options->createAssociations === false) {
                continue;
            }

            // Create associations if the attribute type is set
            if ($attribute->hasType() && $this->isTypeInStructure($attribute->type) && !$this->isAssociationResolved($attribute->type, $associations)) {
                $def .= $this->createNodeRelation(
                    $this->structure[$attribute->type]->identifier(),
                    $class->identifier(),
                    [
                        'dir' => 'back',
                        'arrowtail' => 'none',
                        'style' => 'dashed',
                    ]
                );
                $associations[strtolower($attribute->type)] = true;
            }
        }

        $functions = [];
        /** @var plPhpFunction $function */
        foreach ($class->functions as $function) {
            $functions[] = (string)$function;

            // Association creation is optional
            if ($this->options->createAssociations === false) {
                continue;
            }

            // Create association if the function is the constructor and takes
            // other classes as parameters
            if ($function->isConstructor()) {
                /** @var plPhpFunctionParameter $param */
                foreach ($function->params as $param) {
                    if ($param->hasType() && $this->isTypeInStructure($param->type) && !$this->isAssociationResolved($param->type, $associations)) {
                        $def .= $this->createNodeRelation(
                            $this->structure[$param->type]->identifier(),
                            $class->identifier(),
                            [
                                'dir' => 'back',
                                'arrowtail' => 'none',
                                'style' => 'dashed',
                            ]
                        );
                        $associations[strtolower($param->type)] = true;
                    }
                }
            }
        }

        // Create the node
        $def .= $this->createNode(
            "\"{$class->identifier()}\"",
            [
                'label' => $this->createClassLabel($name, $attributes, $functions),
                'shape' => 'plaintext',
            ]
        );

        // Create class inheritance relation
        if ($class->hasParent()) {
            // Check if we need an "external" class node
            if (!$this->isTypeInStructure($class->extends->name)) {
                $def .= $this->getClassDefinition($class->extends);
            }

            $def .= $this->createNodeRelation(
                "\"{$class->extends->identifier()}\"",
                "\"{$class->identifier()}\"",
                [
                    'dir' => 'back',
                    'arrowtail' => 'empty',
                    'style' => 'solid'
                ]
            );
        }

        // Create class implements relation
        foreach ($class->implements as $interface) {
            // Check if we need an "external" interface node
            if (!$this->isTypeInStructure($interface)) {
                $def .= $this->getInterfaceDefinition($interface);
            }

            $def .= $this->createNodeRelation(
                "\"{$interface->identifier()}\"",
                "\"{$class->identifier()}\"",
                [
                    'dir' => 'back',
                    'arrowtail' => 'normal',
                    'style' => 'dashed',
                ]
            );
        }

        return $def;
    }

    private function getInterfaceDefinition(plPhpInterface $interface)
    {
        $def = '';

        // First we need to create the needed data arrays
        $name = $interface->name;

        $functions = [];
        foreach ($interface->functions as $function) {
            $functions[] = (string)$function;
        }

        // Create the node
        $def .= $this->createNode(
            $interface->identifier(),
            [
                'label' => $this->createInterfaceLabel($name, [], $functions),
                'shape' => 'plaintext',
            ]
        );

        // Create interface inheritance relation
        if ($interface->hasParent()) {
            // Check if we need an "external" interface node
            if (!$this->isTypeInStructure($interface->extends)) {
                $def .= $this->getInterfaceDefinition($interface->extends);
            }

            $def .= $this->createNodeRelation(
                $interface->extends->identifier(),
                $interface->identifier(),
                [
                    'dir' => 'back',
                    'arrowtail' => 'empty',
                    'style' => 'solid'
                ]
            );
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

    private function createNodeRelation($node1, $node2, $options)
    {
        $relation = $node1 . ' -> ' . $node2 . ' [';
        foreach ($options as $key => $value) {
            $relation .= $key . '=' . $value . ' ';
        }
        $relation .= "]\n";
        return $relation;
    }

    private function createInterfaceLabel($name, $attributes, $functions)
    {
        // Start the table
        $label = '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT">';

        // The title
        $label .= '<TR><TD BORDER="' . $this->options->style->interfaceTableBorder . '" ALIGN="CENTER" BGCOLOR="' . $this->options->style->interfaceTitleBackground . '"><FONT COLOR="' . $this->options->style->interfaceTitleColor . '" FACE="' . $this->options->style->interfaceTitleFont . '" POINT-SIZE="' . $this->options->style->interfaceTitleFontsize . '">' . $name . '</FONT></TD></TR>';

        // The attributes block
        $label .= '<TR><TD BORDER="' . $this->options->style->interfaceTableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->options->style->interfaceAttributesBackground . '">';
        if (count($attributes) === 0) {
            $label .= ' ';
        }
        foreach ($attributes as $attribute) {
            $label .= '<FONT COLOR="' . $this->options->style->interfaceAttributesColor . '" FACE="' . $this->options->style->interfaceAttributesFont . '" POINT-SIZE="' . $this->options->style->interfaceAttributesFontsize . '">' . $attribute . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // The function block
        $label .= '<TR><TD BORDER="' . $this->options->style->interfaceTableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->options->style->interfaceFunctionsBackground . '">';
        if (count($functions) === 0) {
            $label .= ' ';
        }
        foreach ($functions as $function) {
            $label .= '<FONT COLOR="' . $this->options->style->interfaceFunctionsColor . '" FACE="' . $this->options->style->interfaceFunctionsFont . '" POINT-SIZE="' . $this->options->style->interfaceFunctionsFontsize . '">' . $function . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // End the table
        $label .= '</TABLE>>';

        return $label;
    }

    private function createClassLabel($name, $attributes, $functions)
    {
        // Start the table
        $label = '<<TABLE CELLSPACING="0" BORDER="0" ALIGN="LEFT">';

        // The title
        $label .= '<TR><TD BORDER="' . $this->options->style->classTableBorder . '" ALIGN="CENTER" BGCOLOR="' . $this->options->style->classTitleBackground . '"><FONT COLOR="' . $this->options->style->classTitleColor . '" FACE="' . $this->options->style->classTitleFont . '" POINT-SIZE="' . $this->options->style->classTitleFontsize . '">' . $name . '</FONT></TD></TR>';

        // The attributes block
        $label .= '<TR><TD BORDER="' . $this->options->style->classTableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->options->style->classAttributesBackground . '">';
        if (count($attributes) === 0) {
            $label .= ' ';
        }
        foreach ($attributes as $attribute) {
            $label .= '<FONT COLOR="' . $this->options->style->classAttributesColor . '" FACE="' . $this->options->style->classAttributesFont . '" POINT-SIZE="' . $this->options->style->classAttributesFontsize . '">' . $attribute . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // The function block
        $label .= '<TR><TD BORDER="' . $this->options->style->classTableBorder . '" ALIGN="LEFT" BGCOLOR="' . $this->options->style->classFunctionsBackground . '">';
        if (count($functions) === 0) {
            $label .= ' ';
        }
        foreach ($functions as $function) {
            $label .= '<FONT COLOR="' . $this->options->style->classFunctionsColor . '" FACE="' . $this->options->style->classFunctionsFont . '" POINT-SIZE="' . $this->options->style->classFunctionsFontsize . '">' . $function . '</FONT><BR ALIGN="LEFT"/>';
        }
        $label .= '</TD></TR>';

        // End the table
        $label .= '</TABLE>>';

        return $label;
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
