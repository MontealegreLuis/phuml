<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use PhUml\Parser\CodeFinder;
use PhUml\Parser\CodeFinderConfiguration;
use PhUml\Parser\CodeParser;
use PhUml\Parser\CodeParserConfiguration;
use PhUml\Parser\SourceCodeFinder;
use PhUml\Processors\GraphvizConfiguration;
use PhUml\Processors\GraphvizProcessor;
use PhUml\Processors\OutputWriter;
use PhUml\Stages\ProgressDisplay;
use Symplify\SmartFileSystem\SmartFileSystem;

final class DigraphConfiguration
{
    private readonly CodeFinder $codeFinder;

    private readonly CodeParser $codeParser;

    private readonly GraphvizProcessor $graphvizProcessor;

    private readonly OutputWriter $writer;

    /**
     * @param array{
     *     recursive: bool,
     *     associations: bool,
     *     "hide-private": bool,
     *     "hide-protected": bool,
     *     "hide-methods": bool,
     *     "hide-attributes": bool,
     *     "hide-empty-blocks": bool,
     *     theme: string
     *  } $options
     */
    public function __construct(array $options, private readonly ProgressDisplay $display)
    {
        $this->codeFinder = SourceCodeFinder::fromConfiguration(new CodeFinderConfiguration($options));
        $this->codeParser = CodeParser::fromConfiguration(new CodeParserConfiguration($options));
        $this->graphvizProcessor = GraphvizProcessor::fromConfiguration(new GraphvizConfiguration($options));
        $this->writer = new OutputWriter(new SmartFileSystem());
    }

    public function codeFinder(): CodeFinder
    {
        return $this->codeFinder;
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

    public function display(): ProgressDisplay
    {
        return $this->display;
    }
}
