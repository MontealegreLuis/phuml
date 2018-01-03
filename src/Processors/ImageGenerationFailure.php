<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use RuntimeException;

class ImageGenerationFailure extends RuntimeException
{
    public function __construct($output)
    {
        parent::__construct("Execution of external program failed:\n{$output}");
    }
}
