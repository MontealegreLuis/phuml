<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Properties;

trait WithProperties
{
    /** @var Property[] */
    protected array $properties;

    /** @return Property[] */
    public function properties(): array
    {
        return $this->properties;
    }
}
