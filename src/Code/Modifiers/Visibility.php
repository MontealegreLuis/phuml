<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

/**
 * It represents the visibility of either an attribute or a method
 */
class Visibility
{
    /** @var string[] */
    private static $symbols = [
        'private' => '-',
        'public' => '+',
        'protected' => '#',
    ];

    /** @var string */
    private $modifier;

    private function __construct(string $modifier)
    {
        $this->modifier = $modifier;
    }

    public static function public(): Visibility
    {
        return new Visibility('public');
    }

    public static function protected(): Visibility
    {
        return new Visibility('protected');
    }

    public static function private(): Visibility
    {
        return new Visibility('private');
    }

    public function equals(Visibility $another): bool
    {
        return $this->modifier === $another->modifier;
    }

    public function __toString()
    {
        return sprintf('%s', self::$symbols[$this->modifier]);
    }
}
