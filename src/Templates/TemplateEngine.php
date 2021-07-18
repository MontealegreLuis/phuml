<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter as Filter;

/** @noRector \Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector */
class TemplateEngine
{
    private Twig $twig;

    public function __construct(Twig $twig = null)
    {
        $this->twig = $twig ?? new Twig(new FilesystemLoader(__DIR__ . '/../resources/templates'));
        $this->twig->addFilter(new Filter(
            'whitespace',
            static fn (string $html): ?string => preg_replace('/\s\s+/', '', $html)
        ));
    }

    /** @param mixed[] $values */
    public function render(string $template, array $values): string
    {
        try {
            return $this->twig->render($template, $values);
        } catch (LoaderError | RuntimeError  | SyntaxError $e) {
            throw new TemplateFailure($e);
        }
    }
}
