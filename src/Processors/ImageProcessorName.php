<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

final class ImageProcessorName
{
    /** @var string[] */
    private const NAMES = ['neato', 'dot'];

    private string $name;

    public function __construct(string $name)
    {
        if (! \in_array($name, self::NAMES, true)) {
            throw UnknownImageProcessor::named($name, self::NAMES);
        }
        $this->name = $name;
    }

    public function command(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return ucfirst($this->name);
    }

    public function isDot(): bool
    {
        return $this->name === 'dot';
    }
}
