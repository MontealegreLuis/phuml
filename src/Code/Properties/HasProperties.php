<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Properties;

interface HasProperties
{
    /** @return Property[] */
    public function properties(): array;
}
