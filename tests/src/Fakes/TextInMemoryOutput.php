<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TextInMemoryOutput implements OutputInterface
{
    private string $output = '';

    public function output(): string
    {
        return $this->output;
    }

    public function write($messages, $newline = false, $options = 0)
    {
        $this->output .= $messages;
    }

    public function writeln($messages, $options = 0)
    {
        $this->output .= $messages . "\n";
    }

    public function setVerbosity($level)
    {
        // nothing to do
    }

    public function getVerbosity()
    {
        return self::VERBOSITY_QUIET;
    }

    public function isQuiet()
    {
        return true;
    }

    public function isVerbose()
    {
        return false;
    }

    public function isVeryVerbose()
    {
        return false;
    }

    public function isDebug()
    {
        return false;
    }

    public function setDecorated($decorated)
    {
        // do nothing
    }

    public function isDecorated()
    {
        return false;
    }

    public function setFormatter(OutputFormatterInterface $formatter)
    {
        // do nothing
    }

    public function getFormatter()
    {
        return new OutputFormatter();
    }
}
