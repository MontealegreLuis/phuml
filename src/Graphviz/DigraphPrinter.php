<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use PhUml\Graphviz\Styles\DigraphStyle;
use PhUml\Graphviz\Styles\ThemeName;
use PhUml\Templates\TemplateEngine;

final class DigraphPrinter
{
    private TemplateEngine $engine;

    private DigraphStyle $style;

    public function __construct(TemplateEngine $engine = null, DigraphStyle $style = null)
    {
        $this->engine = $engine ?? new TemplateEngine();
        $this->style = $style ?? DigraphStyle::default(new ThemeName('phuml'));
    }

    public function toDot(Digraph $digraph): string
    {
        return trim($this->engine->render('digraph.html.twig', [
            'digraph' => $digraph,
            'style' => $this->style,
        ]));
    }
}
