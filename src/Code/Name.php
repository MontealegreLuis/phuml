<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class Name
{
    /** @var string */
    private $name;

    /** @var string[] Packages, sub-packages and class/interface/trait name */
    private $parts;

    public static function from(string $text): Name
    {
        return new Name($text);
    }

    public function namespace(): string
    {
        return implode('\\', \array_slice($this->parts, 0, -1));
    }

    public function __toString()
    {
        return $this->name;
    }

    private function __construct(string $name)
    {
        $this->parts = explode('\\', $name);
        $this->name = $this->parts[substr_count($name, '\\')];
    }
}
