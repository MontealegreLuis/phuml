<?php

class plDotProcessor extends plExternalCommandProcessor
{
    public $options;

    public function __construct()
    {
        $this->options = new plProcessorOptions();
    }

    public function getInputType(): string
    {
        return 'text/dot';
    }

    public function getOutputType(): string
    {
        return 'image/png';
    }

    public function execute(string $infile, string $outfile): void
    {
        exec(
            'dot -Tpng -o ' . escapeshellarg($outfile) . ' ' . escapeshellarg($infile),
            $output,
            $return
        );

        if ($return !== 0) {
            throw new plProcessorExternalExecutionException($output);
        }
    }
}
