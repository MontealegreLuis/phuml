<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use PHPUnit\Framework\TestCase;

class PhUmlListProcessorsOptionTest extends TestCase
{
    /** @test */
    function it_displays_processors_information_if_option_l_is_provided()
    {
        $processors = <<<OUTPUT
phUML Version 0.2 (Jakob Westhoff <jakob@php.net>)
The following processors are available:

* Processor: Graphviz
  - Options:
    style (string):
      Style to use for the dot creation

    createAssociations (boolean):
      Create connections between classes that include each other. (This
      information can only be extracted if it is present in docblock comments)

* Processor: Neato
  - Options:
    This processor does not have any options.

* Processor: Dot
  - Options:
    This processor does not have any options.

* Processor: Statistics
  - Options:
    This processor does not have any options.


OUTPUT;

        passthru(sprintf('php %s -l', __DIR__ . '/../../src/app/phuml'));

        $this->expectOutputString($processors);
    }
}
