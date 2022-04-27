<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Properties;

interface HasConstants
{
    /** @return Constant[] */
    public function constants(): array;
}
