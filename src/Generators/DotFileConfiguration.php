<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Configuration\DigraphConfiguration;
use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeParser;
use PhUml\Parser\CodeParserConfiguration;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputWriter;
use Symplify\SmartFileSystem\SmartFileSystem;

final class DotFileConfiguration
{
    private CodeFinder $sourceCodeFinder;

    private CodeParser $codeParser;

    private GraphvizProcessor $graphvizProcessor;

    private OutputWriter $writer;

    /** @param mixed[] $configuration */
    public function __construct(array $configuration)
    {
        $recursive = (bool) ($configuration['recursive'] ?? false);
        $this->sourceCodeFinder = $recursive ? SourceCodeFinder::recursive() : SourceCodeFinder::nonRecursive();
        $this->codeParser = CodeParser::fromConfiguration(new CodeParserConfiguration($configuration));
        $this->graphvizProcessor = GraphvizProcessor::fromConfiguration(new DigraphConfiguration($configuration));
        $this->writer = new OutputWriter(new SmartFileSystem());
    }

    public function codeFinder(): CodeFinder
    {
        return $this->sourceCodeFinder;
    }

    public function codeParser(): CodeParser
    {
        return $this->codeParser;
    }

    public function graphvizProcessor(): GraphvizProcessor
    {
        return $this->graphvizProcessor;
    }

    public function writer(): OutputWriter
    {
        return $this->writer;
    }
}
