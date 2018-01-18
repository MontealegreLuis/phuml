<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use PHPUnit\Framework\TestCase;
use Twig_Environment;

class TemplateEngineTest extends TestCase
{
    /** @test */
    function it_uses_twig_to_render_a_template()
    {
        $twig = $this->prophesize(Twig_Environment::class);
        $template = 'a-template.html.twig';
        $values = ['value' => 'foo', 'number' => 2];
        $twig->render($template, $values)->willReturn('Yay!');
        $engine = new TemplateEngine($twig->reveal());

        $engine->render($template, $values);

        $twig->render($template, $values)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_fails_if_twig_fails()
    {
        $twig = $this->prophesize(Twig_Environment::class);
        $template = 'a-template.html.twig';
        $values = ['value' => 'foo', 'number' => 2];
        $twig->render($template, $values)->willThrow(\Twig_Error_Syntax::class);
        $engine = new TemplateEngine($twig->reveal());

        $this->expectException(TemplateFailure::class);
        $engine->render($template, $values);
    }

}
