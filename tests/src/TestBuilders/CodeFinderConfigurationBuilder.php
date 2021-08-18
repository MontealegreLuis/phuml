<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\TestBuilders;

use PhUml\Parser\CodeFinderConfiguration;

final class CodeFinderConfigurationBuilder
{
    public function build(): CodeFinderConfiguration
    {
        return new CodeFinderConfiguration(['recursive' => false]);
    }
}
