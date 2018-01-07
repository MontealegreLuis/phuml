<?php
namespace PhUml\Processors;

use RuntimeException;

class InvalidProcessorChain extends RuntimeException
{
    public static function with(Processor $last, Processor $next)
    {
        return new InvalidProcessorChain(sprintf(
            'Two processors in the chain are incompatible. The first processor\'s output is "%s". The next Processor in the queue does only support the following input type: %s.',
            $last->getOutputType(),
            $next->getInputType()
        ));
    }
}
