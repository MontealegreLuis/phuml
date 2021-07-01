<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

final class ImageProcessorName
{
    /** @var string[] */
    private static $names = ['neato', 'dot'];

    /** @var string  */
    private $name;

    public function __construct(?string $name)
    {
        if (! \in_array($name, self::$names, true)) {
            throw UnknownImageProcessor::named($name, self::$names);
        }
        $this->name = $name;
    }

    public function isDot(): bool
    {
        return $this->name === 'dot';
    }
}
