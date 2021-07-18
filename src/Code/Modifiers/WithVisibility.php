<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

trait WithVisibility
{
    private Visibility $modifier;

    public function isPublic(): bool
    {
        return $this->hasVisibility(Visibility::public());
    }

    public function isPrivate(): bool
    {
        return $this->hasVisibility(Visibility::private());
    }

    public function isProtected(): bool
    {
        return $this->hasVisibility(Visibility::protected());
    }

    public function hasVisibility(Visibility $modifier): bool
    {
        return $this->modifier->equals($modifier);
    }
}
