<?php

use PhUml\Parser\CodeFinder;
use PhUml\Parser\TokenParser;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;
use PhUml\Processors\Processor;

class plPhuml
{
    /** @var TokenParser */
    private $parser;

    /** @var string[] */
    private $files;

    /** @var Processor[] */
    private $processors;

    /** @var CodeFinder */
    private $finder;

    public function __construct(TokenParser $parser = null, CodeFinder $finder = null)
    {
        $this->parser = $parser ?? new TokenParser();
        $this->finder = $finder ?? new CodeFinder();
        $this->processors = [];
        $this->files = [];
    }

    public function addDirectory(string $directory, bool $recursive = true): void
    {
        $this->finder->addDirectory($directory, $recursive);
    }

    /** @return string[] */
    public function files(): array
    {
        return $this->finder->files();
    }

    /**
     * @throws InvalidInitialProcessor
     * @throws InvalidProcessorChain
     */
    public function addProcessor(Processor $processor): void
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
        $structure = $this->parser->parse($this->finder);

        $input = $structure;
        foreach ($this->processors as $processor) {
            echo "[|] Running '{$processor->name()}' processor\n";
            $input = $processor->process($input);
        }

        echo "[|] Writing generated data to disk\n";
        end($this->processors)->writeToDisk($input, $outfile);
    }
}
