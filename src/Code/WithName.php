<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

trait WithName
{
    /** @var Name|null */
    private $name;

    public function name(): ?Name
    {
        return $this->name;
    }
}
