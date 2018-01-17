<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use Twig_Environment as Twig;
use Twig_Error_Loader as LoaderError;
use Twig_Error_Runtime as RuntimeError;
use Twig_Error_Syntax as SyntaxError;
use Twig_Loader_Filesystem as FileSystemLoader;

class TemplateEngine
{
    /** @var Twig */
    private $twig;

    public function __construct(Twig $twig = null)
    {
        $this->twig = $twig ?? new Twig(new FileSystemLoader(__DIR__ . '/../resources/templates'));
    }

    public function render($template, array $values): string
    {
        try {
            return $this->twig->render($template, $values);
        } catch (LoaderError | RuntimeError  | SyntaxError $e) {
            throw new TemplateFailure($e);
        }
    }
}
