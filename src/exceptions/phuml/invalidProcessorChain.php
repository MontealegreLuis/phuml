<?php

class plPhumlInvalidProcessorChainException extends Exception
{
    public function __construct(string $outputType, string $inputType)
    {
        parent::__construct(
            'Two processors in the chain are incompatible. The first processor\'s output is "'
            . $outputType
            . '". The next Processor in the queue does only support the following input type: '
            . $inputType
            . '.'
        );
    }
}
