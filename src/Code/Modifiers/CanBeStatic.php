<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

interface CanBeStatic
{
    public function isStatic(): bool;
}
