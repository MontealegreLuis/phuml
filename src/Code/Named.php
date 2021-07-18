<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

interface Named
{
    /**
     * The name of a definition is used by the `Codebase` class to avoid duplicated definitions
     *
     * @see Codebase::has
     * @see Codebase::get
     */
    public function name(): ?Name;
}
