<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Styles;

final class ThemeName
{
    /** @var string[] */
    private static $validNames = ['phuml', 'php', 'classic'];

    /** @var string */
    private $name;

    public static function from(string $text): ThemeName
    {
        return new ThemeName($text);
    }

    public function name(): string
    {
        return $this->name;
    }

    private function __construct(string $name)
    {
        if (!\in_array($name, self::$validNames, true)) {
            throw UnknownTheme::named($name, self::$validNames);
        }
        $this->name = $name;
    }
}
