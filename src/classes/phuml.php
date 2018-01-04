<?php

use PhUml\Parser\TokenParser;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;
use Symfony\Component\Finder\Finder;

class plPhuml
{
    /** @var TokenParser */
    private $parser;

    /** @var string[] */
    private $files;

    /** @var plProcessor[] */
    private $processors;

    /** @var Finder */
    private $finder;

    public function __construct(TokenParser $parser = null, Finder $finder = null)
    {
        $this->parser = $parser ?? new TokenParser();
        $this->finder = $finder ?? new Finder();
        $this->processors = [];
        $this->files = [];
    }

    public function addDirectory(string $directory, bool $recursive = true): void
    {
        if (!$recursive) {
            $this->finder->depth(0);
        }
        $this->finder->in($directory)->files()->name('*.php');
        foreach ($this->finder as $file) {
            $this->files[] = $file->getRealPath();
        }
    }

    /** @return string[] */
    public function files(): array
    {
        return $this->files;
    }

    /**
     * @throws InvalidInitialProcessor
     * @throws InvalidProcessorChain
     */
    public function addProcessor(plProcessor $processor): void
    {
        if (count($this->processors) === 0 && !$processor->isInitial()) {
            throw InvalidInitialProcessor::given($processor);
        }
        $lastProcessor = end($this->processors);
        if (count($this->processors) > 0 && !$lastProcessor->isCompatibleWith($processor)) {
            throw InvalidProcessorChain::with($lastProcessor, $processor);
        }
        $this->processors[] = $processor;
    }

    public function generate($outfile): void
    {
        echo "[|] Parsing class structure\n";
        $structure = $this->parser->parse($this->files);

        $input = $structure;
        foreach ($this->processors as $processor) {
            echo "[|] Running '{$processor->name()}' processor\n";
            $input = $processor->process($input);
        }

        echo "[|] Writing generated data to disk\n";
        end($this->processors)->writeToDisk($input, $outfile);
    }
}
