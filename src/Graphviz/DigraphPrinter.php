<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Templates\TemplateEngine;

final class DigraphPrinter
{
    public function __construct(private readonly TemplateEngine $engine, private readonly DigraphStyle $style)
    {
    }

    public function toDot(Digraph $digraph): string
    {
        return trim($this->engine->render('digraph.html.twig', [
            'digraph' => $digraph,
            'style' => $this->style,
        ]));
    }
}
