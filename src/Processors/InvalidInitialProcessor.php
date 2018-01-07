<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use RuntimeException;

class InvalidInitialProcessor extends RuntimeException
{
    /**
     * @param Processor $processor
     * @return InvalidInitialProcessor
     */
    public static function given(Processor $processor)
    {
        return new InvalidInitialProcessor(sprintf(
            'Given processor does not support input type "%s", input type "%s" found',
            Processor::INITIAL_INPUT_TYPE,
            $processor->getInputType()
        ));
    }
}