<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class PhUmlHelpOptionTest extends TestCase
{
    private $help = <<<HELP
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
Usage: phuml [-h|-l] [OPTIONS] <DIRECTORY> <PROCESSOR> [PROCESSOR OPTIONS] ... <OUTFILE>

Commands:
    -h      Display this help text
    -l      List all available processors

Options: 
    -r      Scan given directorie recursively

Example:
    phuml -r ./ -graphviz -createAssociations false -neato out.png

    This example will scan the current directory recursively for php files.
    Send them to the "dot" processor which will process them with the option
    "createAssociations" set to false. After that it will be send to the neato
    processor and saved to the file out.png


HELP;

    /** @test */
    function it_displays_help_if_option_h_is_provided()
    {
        passthru(sprintf('php %s -h', __DIR__ . '/../../src/app/phuml'));

        $this->expectOutputString($this->help);
    }

    /** @test */
    function it_displays_help_if_no_arguments_are_provided()
    {
        passthru(sprintf('php %s', __DIR__ . '/../../src/app/phuml'));

        $this->expectOutputString($this->help);
    }

    /** @test */
    function it_displays_help_if_an_invalid_directory_is_provided()
    {
        passthru(sprintf('php %s unknown_directory', __DIR__ . '/../../src/app/phuml'));

        $this->expectOutputString($this->help);
    }
}
