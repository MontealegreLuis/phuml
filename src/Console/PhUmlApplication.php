<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Console;

use PhUml\Console\Commands\GenerateClassDiagramCommand;
use PhUml\Console\Commands\GenerateDotFileCommand;
use PhUml\Console\Commands\GenerateStatisticsCommand;
use Symfony\Component\Console\Application;

class PhUmlApplication extends Application
{
    /**
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(ProgressDisplay $display)
    {
        parent::__construct('phUML', '1.0.0');
        $this->add(new GenerateClassDiagramCommand($display));
        $this->add(new GenerateStatisticsCommand($display));
        $this->add(new GenerateDotFileCommand($display));
    }
}
