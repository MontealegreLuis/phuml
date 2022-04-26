<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Properties;

trait WithConstants
{
    /** @var Constant[] */
    protected array $constants;

    /** @return Constant[] */
    public function constants(): array
    {
        return $this->constants;
    }
}
