<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use plProcessor;
use RuntimeException;

class InvalidInitialProcessor extends RuntimeException
{
    /**
     * @param plProcessor $processor
     * @return InvalidInitialProcessor
     */
    public static function given(plProcessor $processor)
    {
        return new InvalidInitialProcessor(sprintf(
            'Given processor does not support input type "%s", input type "%s" found',
            plProcessor::INITIAL_INPUT_TYPE,
            $processor->getInputType()
        ));
    }
}