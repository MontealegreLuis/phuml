<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use plGraphvizProcessorStyle;
use plPhpClass;
use plPhpInterface;
use Twig_Environment as TemplateEngine;
use Twig_Error_Loader as LoaderError;
use Twig_Error_Runtime as RuntimeError;
use Twig_Error_Syntax as SyntaxError;

class NodeLabelBuilder
{
    /** @var TemplateEngine */
    private $engine;

    /** @var plGraphvizProcessorStyle */
    private $style;

    public function __construct(TemplateEngine $engine, plGraphvizProcessorStyle $style)
    {
        $this->engine = $engine;
        $this->style = $style;
    }

    public function labelForClass(plPhpClass $class): string
    {
        return $this->buildLabel('class.html.twig', [
            'class' => $class,
            'style' => $this->style,
        ]);
    }

    public function labelForInterface(plPhpInterface $interface): string
    {
        return $this->buildLabel('interface.html.twig', [
            'interface' => $interface,
            'style' => $this->style,
        ]);
    }

    private function buildLabel(string $template, array $options): string
    {
        try {
            return "<{$this->removeNewLinesFrom($this->engine->render($template, $options))}>";
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            throw new NodeLabelError($e);
        }
    }

    private function removeNewLinesFrom(string $label): string
    {
        return trim(preg_replace('/\s\s+|\n/', '', $label));
    }
}
