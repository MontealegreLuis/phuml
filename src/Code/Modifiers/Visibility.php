<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

use Stringable;
use Webmozart\Assert\Assert;

/**
 * It represents the visibility of either an attribute or a method
 */
final class Visibility implements Stringable
{
    /** @var string[] */
    private const SYMBOLS = [
        'private' => '-',
        'public' => '+',
        'protected' => '#',
    ];

    private string $modifier;

    public function __construct(string $modifier)
    {
        Assert::oneOf($modifier, array_keys(self::SYMBOLS));
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

    public function __toString(): string
    {
        return self::SYMBOLS[$this->modifier];
    }
}
