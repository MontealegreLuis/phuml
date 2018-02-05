<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\DocBlock;
use PhUml\Code\TypeDeclaration;

/**
 * It used to extract the return type of a method
 */
class MethodDocBlock extends DocBlock
{
    /** @var string */
    private static $returnExpression = '/@return\s*([\w]+(\[\])?)/';

    /** @var string */
    private static $parameterExpression = '/@param\s*([\w]+(?:\[\])?)\s*(\$[\w]+)/';

    /** @var TypeDeclaration[] */
    private $parameters;

    public function __construct(?string $comment)
    {
        parent::__construct($comment);
        $this->setParameters();
    }

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

    public function typeOfParameter(string $parameterName): TypeDeclaration
    {
        return $this->parameters[$parameterName] ?? TypeDeclaration::absent();
    }

    private function setParameters(): void
    {
        if (!preg_match_all(self::$parameterExpression, $this->comment, $matches)) {
            return;
        }
        foreach ($matches[0] as $typeHint) {
            $this->extractDeclarationFrom($typeHint);
        }
    }

    private function extractDeclarationFrom(string $typeHint): void
    {
        if (preg_match(self::$parameterExpression, $typeHint, $match)) {
            [$_, $type, $parameterName] = $match;
            $this->parameters[$parameterName] = TypeDeclaration::from($type);
        }
    }
}
