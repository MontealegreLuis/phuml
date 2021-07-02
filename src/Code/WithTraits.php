<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

trait WithTraits
{
    /** @var Name[] */
    protected $traits;

    /** @return Name[] */
    public function traits(): array
    {
        return $this->traits;
    }
}
