<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Processors;

use PhUml\Code\Structure;
use PhUml\Code\Summary;
use Twig_Environment as TemplateEngine;
use Twig_Loader_Filesystem as Filesystem;

/**
 * It takes a code `Structure` and extracts a `Summary` of its contents as text
 */
class StatisticsProcessor extends Processor
{
    /** @var TemplateEngine */
    private $engine;

    public function __construct(TemplateEngine $engine = null)
    {
        $this->engine = $engine ?? new TemplateEngine(
            new FileSystem(__DIR__ . '/../resources/templates')
        );
    }

    public function name(): string
    {
        return 'Statistics';
    }

    /**
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function process(Structure $structure): string
    {
        $summary = new Summary();
        $summary->from($structure);

        return $this->engine->render('statistics.txt.twig', ['summary' => $summary]);
    }
}
