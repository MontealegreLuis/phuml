<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PHPUnit\Framework\TestCase;
use PhUml\Parser\CodebaseDirectory;
use PhUml\TestBuilders\A;

final class DigraphConfigurationTest extends TestCase
{
    /** @test */
    function it_configures_a_code_finder()
    {
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions');
        $recursiveConfiguration = A::digraphConfiguration()->recursive()->build();
        $nonRecursiveConfiguration = A::digraphConfiguration()->build();
        $typeCastedConfiguration = A::digraphConfiguration()->withOverriddenOptions(['recursive' => true])->build();

        $recursiveFinder = $recursiveConfiguration->codeFinder();
        $nonRecursiveFinder = $nonRecursiveConfiguration->codeFinder();
        $typeCastedRecursiveFinder = $typeCastedConfiguration->codeFinder();

        $this->assertCount(8, $recursiveFinder->find($directory)->fileContents());
        $this->assertCount(0, $nonRecursiveFinder->find($directory)->fileContents());
        $this->assertCount(8, $typeCastedRecursiveFinder->find($directory)->fileContents());
    }
}
