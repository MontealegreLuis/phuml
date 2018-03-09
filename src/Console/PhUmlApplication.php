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

/**
 * Console application to generate UML class diagrams and the statistics of an OO codebase
 *
 * It provides 3 commands
 *
 * 1. `phuml:diagram` to generate a class diagram in `png` format
 * 2. `phuml:statistics` to generate a text file with statistics
 * 3. `phuml:dot` to generate a text file with a digraph in DOT format ready to create a class diagram
 */
class PhUmlApplication extends Application
{
    /**
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct(ProgressDisplay $display)
    {
        parent::__construct('phUML', '@package_version@');
        $this->add(new GenerateClassDiagramCommand($display));
        $this->add(new GenerateStatisticsCommand($display));
        $this->add(new GenerateDotFileCommand($display));
    }
}
