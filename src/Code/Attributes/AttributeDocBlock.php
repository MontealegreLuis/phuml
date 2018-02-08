<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\DocBlock;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It creates a type declaration for an attribute from its `@var` tag
 */
class AttributeDocBlock extends DocBlock
{
    /** @var string */
    private static $varExpression = '/@var\s*([\w]+(\[\])?)/';

    public static function from(string $text): AttributeDocBlock
    {
        return new AttributeDocBlock($text);
    }

    public function extractType(): TypeDeclaration
    {
        $type = null;
        if (preg_match(self::$varExpression, $this->comment, $matches)) {
            $type = trim($matches[1]);
        }
        return TypeDeclaration::from($type);
    }
}
