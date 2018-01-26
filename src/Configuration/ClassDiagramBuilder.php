<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Generators\ClassDiagramGenerator;
use PhUml\Processors\DotProcessor;
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\NeatoProcessor;

class ClassDiagramBuilder extends DigraphBuilder
{
    public function __construct(ClassDiagramConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function classDiagramGenerator(): ClassDiagramGenerator
    {
        return new ClassDiagramGenerator(
            $this->codeParser(),
            $this->digraphProcessor(),
            $this->imageProcessor()
        );
    }

    private function imageProcessor(): ImageProcessor
    {
        return $this->configuration->isDotProcessor() ? new DotProcessor() : new NeatoProcessor();
    }
}
