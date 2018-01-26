<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Generators\DotFileGenerator;

class DotFileBuilder extends DigraphBuilder
{
    public function __construct(DigraphConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function dotFileGenerator(): DotFileGenerator
    {
        return new DotFileGenerator($this->codeParser(), $this->digraphProcessor());
    }
}
