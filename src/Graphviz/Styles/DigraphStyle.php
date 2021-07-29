<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

/**
 * It is a container for the partial files used to build the HTML label for the node
 */
final class DigraphStyle
{
    private string $theme;

    public static function default(ThemeName $theme): DigraphStyle
    {
        return new DigraphStyle($theme, 'partials/_attributes.html.twig', 'partials/_methods.html.twig');
    }

    public static function withoutEmptyBlocks(ThemeName $theme): DigraphStyle
    {
        return new DigraphStyle($theme, 'partials/_empty-attributes.html.twig', 'partials/_empty-methods.html.twig');
    }

    private function __construct(ThemeName $theme, private string $attributes, private string $methods)
    {
        $this->theme = "{$theme->name()}.html.twig";
    }

    public function attributes(): string
    {
        return $this->attributes;
    }

    public function methods(): string
    {
        return $this->methods;
    }

    public function theme(): string
    {
        return $this->theme;
    }
}
