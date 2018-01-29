<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Templates\TemplateEngine;

/**
 * It creates an HTML table out of either a class or an interface.
 *
 * The table is used as a label for the nodes in the digraph that is used to create the class diagram
 */
class NodeLabelBuilder
{
    /** @var TemplateEngine */
    private $engine;

    /** @var NodeLabelStyle */
    private $style;

    public function __construct(TemplateEngine $engine, NodeLabelStyle $style = null)
    {
        $this->engine = $engine;
        $this->style = $style ?? new NodeLabelStyle();
    }

    public function forClass(ClassDefinition $class): string
    {
        return $this->buildLabel('class.html.twig', [
            'class' => $class,
            'attributes' => $this->style->attributes(),
            'methods' => $this->style->methods(),
        ]);
    }

    public function forInterface(InterfaceDefinition $interface): string
    {
        return $this->buildLabel('interface.html.twig', [
            'interface' => $interface,
            'attributes' => $this->style->attributes(),
            'methods' => $this->style->methods(),
        ]);
    }

    private function buildLabel(string $template, array $options): string
    {
        return "<{$this->removeNewLinesFrom($this->engine->render($template, $options))}>";
    }

    private function removeNewLinesFrom(string $label): string
    {
        return trim(preg_replace('/\s\s+|\n/', '', $label));
    }
}
