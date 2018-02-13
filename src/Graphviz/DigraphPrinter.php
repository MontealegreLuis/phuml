<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Graphviz\Styles\DefaultDigraphStyle;
use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Templates\TemplateEngine;

class DigraphPrinter
{
    /** @var TemplateEngine */
    private $engine;

    /** @var DigraphStyle */
    private $style;

    public function __construct(TemplateEngine $engine = null, DigraphStyle $style = null)
    {
        $this->engine = $engine ?? new TemplateEngine();
        $this->style = $style ?? new DefaultDigraphStyle();
    }

    public function toDot(Digraph $digraph): string
    {
        return trim($this->engine->render('digraph.html.twig', [
            'digraph' => $digraph,
            'style' => $this->style,
        ]));
    }
}
