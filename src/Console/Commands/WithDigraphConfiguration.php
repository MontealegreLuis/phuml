<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * There are 8 options to generate the digraph for a class diagram
 *
 * 1. `recursive`. If present it will look recursively within the `directory` provided
 * 2. `associations`. If present the command will generate associations between the classes/interfaces
 *    injected through the constructor and the attributes of a class
 * 3. `hide-private` If present it will show only public and protected members
 * 4. `hide-protected` If present it will show only public and private members
 * 5. `hide-methods` If present it will show only attributes and constants
 * 6. `hide-attributes` If present it will show only methods
 * 7. `hide-attributes` If present it will not produce a table row if no attributes or methods are
 *    present
 * 8. `theme` There are 3 color schemes (themes) that you can choose from: `phuml`, which is the
 *    default one, `php` and `classic`
 */
trait WithDigraphConfiguration
{
    private function addDigraphOptions(Command $command): void
    {
        $command
            ->addOption(
                'recursive',
                'r',
                InputOption::VALUE_NONE,
                'Look for classes in the given directory and its sub-directories'
            )
            ->addOption(
                'associations',
                'a',
                InputOption::VALUE_NONE,
                'Extract associations between classes from constructor parameters and attributes'
            )
            ->addOption(
                'hide-private',
                'i',
                InputOption::VALUE_NONE,
                'Ignore private attributes, constants and methods'
            )
            ->addOption(
                'hide-protected',
                'o',
                InputOption::VALUE_NONE,
                'Ignore protected attributes, constants and methods'
            )
            ->addOption(
                'hide-methods',
                'm',
                InputOption::VALUE_NONE,
                'Ignore all methods'
            )
            ->addOption(
                'hide-attributes',
                't',
                InputOption::VALUE_NONE,
                'Ignore all attributes and constants'
            )
            ->addOption(
                'hide-empty-blocks',
                'b',
                InputOption::VALUE_NONE,
                'Do not generate empty blocks for attributes or methods'
            )
            ->addOption(
                'theme',
                'e',
                InputOption::VALUE_OPTIONAL,
                'Colors and fonts to be used for the diagram [phuml, php, classic]',
                'phuml'
            )
        ;
    }
}
