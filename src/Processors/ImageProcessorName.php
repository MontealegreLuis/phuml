<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

class ImageProcessorName
{
    /** @var string[] */
    private static $names = ['neato', 'dot'];

    /** @var string  */
    private $name;

    public static function from(?string $text): ImageProcessorName
    {
        return new ImageProcessorName($text);
    }

    public function is(string $name): bool
    {
        return $this->name === $name;
    }

    private function __construct(?string $name)
    {
        if (!\in_array($name, self::$names, true)) {
            throw UnknownImageProcessor::named($name, self::$names);
        }
        $this->name = $name;
    }
}