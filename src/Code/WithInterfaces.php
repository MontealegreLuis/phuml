<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

trait WithInterfaces
{
    /** @var Name[] */
    private array $interfaces;

    /** @return Name[] */
    public function interfaces(): array
    {
        return $this->interfaces;
    }
}
