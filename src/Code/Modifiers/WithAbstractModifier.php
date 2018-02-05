<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

trait WithAbstractModifier
{
    /** @var bool */
    protected $isAbstract;

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }
}
