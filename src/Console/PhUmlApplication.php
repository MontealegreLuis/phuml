<?php declare(strict_types=1);
/**
 * PHP version 8.0
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
final class PhUmlApplication extends Application
{
    public function __construct()
    {
        // This will be replaced by Box with a version number if it's a PHAR, 1.6.1 for instance
        parent::__construct('phUML', '@package_version@');
        $this->add(new GenerateClassDiagramCommand());
        $this->add(new GenerateStatisticsCommand());
        $this->add(new GenerateDotFileCommand());
    }
}
