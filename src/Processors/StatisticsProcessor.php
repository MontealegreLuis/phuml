<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\Code\Structure;
use PhUml\Code\Summary;

class StatisticsProcessor extends Processor
{
    public function name(): string
    {
        return 'Statistics';
    }

    public function process(Structure $structure): string
    {
        $summary = new Summary();
        $summary->from($structure);

        // Generate the needed text output
        return <<<END
Phuml generated statistics
==========================

General statistics
------------------

Classes:    {$summary->classCount()}
Interfaces: {$summary->interfaceCount()}

Attributes: {$summary->attributeCount()} ({$summary->typedAttributeCount()} are typed)
    * private:   {$summary->privateAttributeCount()}
    * protected: {$summary->protectedAttributeCount()}
    * public:    {$summary->publicAttributeCount()}

Functions:  {$summary->functionCount()} 
    * private:   {$summary->privateFunctionCount()}
    * protected: {$summary->protectedFunctionCount()}
    * public:    {$summary->publicFunctionCount()}

Average statistics
------------------

Attributes per class: {$summary->attributesPerClass()}
Functions per class:  {$summary->functionsPerClass()}

END;
    }
}
