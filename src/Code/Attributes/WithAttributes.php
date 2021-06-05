<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

trait WithAttributes
{
    /** @var Attribute[] */
    protected $attributes;

    /** @return Attribute[] */
    public function attributes(): array
    {
        return $this->attributes;
    }
}
