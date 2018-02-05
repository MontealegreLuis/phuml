<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

trait WithStaticModifier
{
    /** @var bool */
    protected $isStatic;

    public function isStatic(): bool
    {
        return $this->isStatic;
    }
}
