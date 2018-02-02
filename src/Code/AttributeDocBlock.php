<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class AttributeDocBlock
{
    private static $typeExpression = '/@var\s*([\w]+(\[\])?)/';

    /** @var string */
    private $comment;

    public static function from(string $text): AttributeDocBlock
    {
        return new AttributeDocBlock($text);
    }

    private function __construct(string $text)
    {
        $this->comment = $text;
    }

    public function getType(): TypeDeclaration
    {
        $type = null;
        if (preg_match(self::$typeExpression, $this->comment, $matches)) {
            $type = trim($matches[1]);
        }
        return TypeDeclaration::from($type);
    }
}
