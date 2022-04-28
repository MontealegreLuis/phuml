<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

trait WithVisibility
{
    private readonly Visibility $visibility;

    public function isPublic(): bool
    {
        return $this->visibility === Visibility::PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->visibility === Visibility::PRIVATE;
    }

    public function isProtected(): bool
    {
        return $this->visibility === Visibility::PROTECTED;
    }

    public function hasVisibility(Visibility $visibility): bool
    {
        return $this->visibility === $visibility;
    }
}
