<?php

use PhUml\Parser\TokenParser;
use PhUml\Processors\InvalidInitialProcessor;
use PhUml\Processors\InvalidProcessorChain;

class plPhuml
{
    /** @var TokenParser */
    public $generator;

    /** @var string[] */
    private $files;

    /** @var plProcessor[] */
    private $processors;

    public function __construct()
    {
        $this->generator = new TokenParser();
        $this->processors = [];
        $this->files = [];
    }

    public function addDirectory(string $directory, string $extension = 'php', bool $recursive = true)
    {
        if (!$recursive) {
            $iterator = new DirectoryIterator($directory);
        } else {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
        }

        foreach ($iterator as $entry) {
            if (!$entry->isDir() && $entry->getExtension() === $extension) {
                $this->files[] = $entry->getPathname();
            }
        }
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

    public function generate($outfile)
    {
        echo "[|] Parsing class structure\n";
        $structure = $this->generator->createStructure($this->files);

        $input = $structure;
        foreach ($this->processors as $processor) {
            echo "[|] Running '{$processor->name()}' processor\n";
            $input = $processor->process($input);
        }

        echo "[|] Writing generated data to disk\n";
        end($this->processors)->writeToDisk($input, $outfile);
    }
}
