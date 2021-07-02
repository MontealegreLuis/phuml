<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use Webmozart\Assert\Assert;

final class Name
{
    private string $name;

    public function __construct(string $name)
    {
        Assert::notEmpty($name, 'Definition name cannot be null or empty');
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}
