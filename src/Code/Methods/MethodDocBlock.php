<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\DocBlock;
use PhUml\Code\Variables\TypeDeclaration;

/**
 * It extracts the return type and parameters type of a method
 */
final class MethodDocBlock extends DocBlock
{
    private static string $returnExpression = '/@return\s*([\w]+(\[\])?)/';

    private static string $parameterExpression = '/@param\s*([\w]+(?:\[\])?)\s*(\$[\w]+)/';

    /** @var TypeDeclaration[] */
    private array $parameters = [];

    public static function from(?string $comment): MethodDocBlock
    {
        return new MethodDocBlock($comment);
    }

    public function returnType(): TypeDeclaration
    {
        $type = null;
        if (preg_match(self::$returnExpression, (string) $this->comment, $matches) === 1) {
            $type = trim($matches[1]);
        }
        return TypeDeclaration::from($type);
    }

    public function typeOfParameter(string $parameterName): TypeDeclaration
    {
        return $this->parameters[$parameterName] ?? TypeDeclaration::absent();
    }

    protected function __construct(?string $comment)
    {
        parent::__construct($comment);
        $this->setParameters();
    }

    private function setParameters(): void
    {
        if (preg_match_all(self::$parameterExpression, (string) $this->comment, $matches) < 1) {
            return;
        }
        foreach ($matches[0] as $typeHint) {
            $this->extractDeclarationFrom($typeHint);
        }
    }

    private function extractDeclarationFrom(string $typeHint): void
    {
        if (preg_match(self::$parameterExpression, $typeHint, $match) === 1) {
            [$_, $type, $parameterName] = $match;
            $this->parameters[$parameterName] = TypeDeclaration::from($type);
        }
    }
}
