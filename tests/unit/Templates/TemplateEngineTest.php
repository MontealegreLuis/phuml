<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\TwigFilter;

final class TemplateEngineTest extends TestCase
{
    use ProphecyTrait;

    /** @test */
    function it_uses_twig_to_render_a_template()
    {
        $twig = $this->prophesize(Environment::class);
        $template = 'a-template.html.twig';
        $values = ['value' => 'foo', 'number' => 2];
        $twig->addFilter(Argument::type(TwigFilter::class))->shouldBeCalled();
        $twig->render($template, $values)->willReturn('Yay!');
        $engine = new TemplateEngine($twig->reveal());

        $engine->render($template, $values);

        $twig->render($template, $values)->shouldHaveBeenCalled();
    }

    /** @test */
    function it_fails_if_twig_fails()
    {
        $twig = $this->prophesize(Environment::class);
        $template = 'a-template.html.twig';
        $values = ['value' => 'foo', 'number' => 2];
        $twig->addFilter(Argument::type(TwigFilter::class))->shouldBeCalled();
        $twig->render($template, $values)->willThrow(SyntaxError::class);
        $engine = new TemplateEngine($twig->reveal());

        $this->expectException(TemplateFailure::class);
        $engine->render($template, $values);
    }
}
