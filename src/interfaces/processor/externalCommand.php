<?php

abstract class plExternalCommandProcessor extends plProcessor
{

    abstract public function execute(string $infile, string $outfile): void;

    public function process($input)
    {
        // Create temporary datafiles
        $infile = tempnam('/tmp', 'phuml');
        $outfile = tempnam('/tmp', 'phuml');

        file_put_contents($infile, $input);

        unlink($outfile);

        $this->execute($infile, $outfile);

        $outdata = file_get_contents($outfile);

        unlink($infile);
        unlink($outfile);

        return $outdata;
    }
}
