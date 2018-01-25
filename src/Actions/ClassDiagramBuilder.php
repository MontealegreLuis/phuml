<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use PhUml\Parser\CodeParser;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\NeatoProcessor;

class ClassDiagramBuilder
{
    public static function from(ClassDiagramConfiguration $configuration): GenerateClassDiagram
    {
        $dotProcessor = new GraphvizProcessor();
        if ($configuration->extractAssociations()) {
            $dotProcessor->createAssociations();
        }

        $action = new GenerateClassDiagram(new CodeParser(), $dotProcessor);

        if ($configuration->imageProcessor() === 'dot') {
            $action->setImageProcessor(new DotProcessor());
        } else {
            $action->setImageProcessor(new NeatoProcessor());
        }

        return $action;
    }
}
