<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

trait WithTraits
{
    /** @var Name[] */
    protected array $traits;

    /** @return Name[] */
    public function traits(): array
    {
        return $this->traits;
    }
}
