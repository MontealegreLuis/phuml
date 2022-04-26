<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
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
use PhUml\Processors\ImageProcessor;
use PhUml\Processors\ImageProcessorName;
use PhUml\Processors\OutputWriter;
use PhUml\Stages\ProgressDisplay;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ClassDiagramConfiguration
{
    private readonly CodeFinder $codeFinder;

    private readonly CodeParser $codeParser;

    private readonly GraphvizProcessor $graphvizProcessor;

    private readonly ImageProcessor $imageProcessor;

    private readonly OutputWriter $writer;

    /**
     * @param array{
     *     processor: string,
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
        $imageProcessorName = new ImageProcessorName($options['processor']);
        $filesystem = new SmartFileSystem();
        $this->imageProcessor = $imageProcessorName->isDot()
            ? ImageProcessor::dot($filesystem)
            : ImageProcessor::neato($filesystem);
        $this->writer = new OutputWriter($filesystem);
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

    public function imageProcessor(): ImageProcessor
    {
        return $this->imageProcessor;
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
