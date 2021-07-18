<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\CodeParserConfiguration;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\OutputWriter;
use PhUml\Processors\StatisticsProcessor;
use PhUml\Stages\ProgressDisplay;
use PhUml\Templates\TemplateEngine;
use Symplify\SmartFileSystem\SmartFileSystem;

final class StatisticsGeneratorConfiguration
{
    private CodeFinder $codeFinder;

    private CodeParser $codeParser;

    private StatisticsProcessor $statisticsProcessor;

    private OutputWriter $writer;

    private ProgressDisplay $display;

    /** @param mixed[] $options*/
    public function __construct(array $options, ProgressDisplay $display)
    {
        $recursive = (bool) ($options['recursive'] ?? false);
        $this->codeFinder = $recursive ? SourceCodeFinder::recursive() : SourceCodeFinder::nonRecursive();
        $this->codeParser = CodeParser::fromConfiguration(new CodeParserConfiguration($options));
        $this->statisticsProcessor = new StatisticsProcessor(new TemplateEngine());
        $this->writer = new OutputWriter(new SmartFileSystem());
        $this->display = $display;
    }

    public function codeParser(): CodeParser
    {
        return $this->codeParser;
    }

    public function codeFinder(): CodeFinder
    {
        return $this->codeFinder;
    }

    public function statisticsProcessor(): StatisticsProcessor
    {
        return $this->statisticsProcessor;
    }

    public function writer(): OutputWriter
    {
        return $this->writer;
    }

    public function display(): ProgressDisplay
    {
        return $this->display;
    }
}
