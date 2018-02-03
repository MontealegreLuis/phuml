<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class MethodDocBlock extends DocBlock
{
    private static $returnExpression = '/@return\s*([\w]+(\[\])?)/';

    public static function from(?string $text): MethodDocBlock
    {
        return new MethodDocBlock($text);
    }

    public function returnType(): TypeDeclaration
    {
        $type = null;
        if (preg_match(self::$returnExpression, $this->comment, $matches)) {
            $type = trim($matches[1]);
        }
        return TypeDeclaration::from($type);
    }
}
