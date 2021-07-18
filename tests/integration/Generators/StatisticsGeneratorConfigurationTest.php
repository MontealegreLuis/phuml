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

final class StatisticsGeneratorConfigurationTest extends TestCase
{
    /** @test */
    function it_configures_a_recursive_code_finder()
    {
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions');
        $configuration = A::statisticsGeneratorConfiguration()->recursive()->build();

        $finder = $configuration->codeFinder();

        $sourceCode = $finder->find($directory);
        $this->assertCount(8, $sourceCode->fileContents());
    }

    /** @test */
    function it_configures_a_non_recursive_code_finder()
    {
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions');
        $configuration = A::statisticsGeneratorConfiguration()->build();

        $finder = $configuration->codeFinder();

        $sourceCode = $finder->find($directory);
        $this->assertCount(0, $sourceCode->fileContents());
    }

    /** @test */
    function it_casts_to_boolean_the_recursive_option()
    {
        $directory = new CodebaseDirectory(__DIR__ . '/../../resources/.code/exceptions');
        $configuration = A::statisticsGeneratorConfiguration()->withOverriddenOptions(['recursive' => '1'])->build();

        $finder = $configuration->codeFinder();

        $sourceCode = $finder->find($directory);
        $this->assertCount(8, $sourceCode->fileContents());
    }
}
