<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Parser\CodeParser;
use PhUml\Parser\CodeParserConfiguration;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\OutputWriter;
use PhUml\Processors\StatisticsProcessor;
use Symplify\SmartFileSystem\SmartFileSystem;

final class StatisticsGeneratorConfiguration
{
    private CodeParser $codeParser;

    private SourceCodeFinder $sourceCodeFinder;

    private StatisticsProcessor $statisticsProcessor;

    private OutputWriter $writer;

    /** @param mixed[] $configuration */
    public function __construct(array $configuration)
    {
        $recursive = $configuration['recursive'] ?? false;
        $this->sourceCodeFinder = (bool) $recursive ? SourceCodeFinder::recursive() : SourceCodeFinder::nonRecursive();
        $this->codeParser = CodeParser::fromConfiguration(new CodeParserConfiguration($configuration));
        $this->statisticsProcessor = new StatisticsProcessor();
        $this->writer = new OutputWriter(new SmartFileSystem());
    }

    public function codeParser(): CodeParser
    {
        return $this->codeParser;
    }

    public function sourceCodeFinder(): SourceCodeFinder
    {
        return $this->sourceCodeFinder;
    }

    public function statisticsProcessor(): StatisticsProcessor
    {
        return $this->statisticsProcessor;
    }

    public function writer(): OutputWriter
    {
        return $this->writer;
    }
}
