<?php

use PhUml\Parser\TokenParser;

class plPhuml
{
    private $properties;

    private $files;
    private $processors;

    public function __construct()
    {
        $this->properties = [
            'generator' => new TokenParser()
        ];

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
     * @throws plPhumlInvalidProcessorChainException
     */
    public function addProcessor(plProcessor $processor)
    {
        if (count($this->processors) === 0) {
            // First processor must support application/phuml-structure
            if ('application/phuml-structure' !== $processor->getInputType()) {
                throw new plPhumlInvalidProcessorChainException('application/phuml-structure', $processor->getInputType());
            }
        } else {
            $this->checkProcessorCompatibility(end($this->processors), $processor);
        }
        $this->processors[] = $processor;
    }

    /**
     * @throws plPhumlInvalidProcessorChainException
     */
    private function checkProcessorCompatibility(plProcessor $first, plProcessor $second)
    {
        if (!$first->isCompatibleWith($second)) {
            throw new plPhumlInvalidProcessorChainException($first->getOutputType(), $second->getInputType());
        }
    }

    public function generate($outfile)
    {
        echo "[|] Parsing class structure", "\n";
        $structure = $this->generator->createStructure($this->files);

        $input = $structure;
        foreach ($this->processors as $processor) {
            preg_match(
                '@^pl([A-Z][a-z]*)Processor$@',
                get_class($processor),
                $matches
            );

            echo "[|] Running '" . $matches[1] . "' processor", "\n";
            $input = $processor->process($input);
        }

        echo "[|] Writing generated data to disk", "\n";
        end($this->processors)->writeToDisk($input, $outfile);
    }


    public function __get($key)
    {
        if (!array_key_exists($key, $this->properties)) {
            throw new plBasePropertyException($key, plBasePropertyException::READ);
        }
        return $this->properties[$key];
    }

    public function __set($key, $val)
    {
        if (!array_key_exists($key, $this->properties)) {
            throw new plBasePropertyException($key, plBasePropertyException::WRITE);
        }
        $this->properties[$key] = $val;
    }
}
