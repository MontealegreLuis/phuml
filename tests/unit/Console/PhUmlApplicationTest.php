<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Console;

use PHPUnit\Framework\TestCase;

final class PhUmlApplicationTest extends TestCase
{
    /** @test */
    function it_knows_its_name_and_version()
    {
        $application = new PhUmlApplication();

        $this->assertEquals('phUML', $application->getName());
        $this->assertEquals('@package_version@', $application->getVersion());
    }
}
