<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\Variables\TypeDeclaration;

/**
 * It extracts the return type and parameters type of a method
 */
final class MethodDocBlock
{
    private const RETURN_EXPRESSION = '/@return\s*([\w]+(\[\])?)/';

    private const PARAMETER_EXPRESSION = '/@param\s*([\w]+(?:\[\])?)\s*(\$[\w]+)/';

    private TypeDeclaration $returnType;

    /** @var TypeDeclaration[] */
    private array $parametersTypes = [];

    public function __construct(?string $comment)
    {
        $this->extractParametersTypes($comment);
        $this->extractReturnType($comment);
    }

    public function returnType(): TypeDeclaration
    {
        return $this->returnType;
    }

    public function typeOfParameter(string $parameterName): TypeDeclaration
    {
        return $this->parametersTypes[$parameterName] ?? TypeDeclaration::absent();
    }

    private function extractReturnType(?string $comment): void
    {
        if (preg_match(self::RETURN_EXPRESSION, (string) $comment, $matches) === 1) {
            $this->returnType = TypeDeclaration::from(trim($matches[1]));
            return;
        }

        $this->returnType = TypeDeclaration::absent();
    }

    private function extractParametersTypes(?string $comment): void
    {
        if (preg_match_all(self::PARAMETER_EXPRESSION, (string) $comment, $matches) < 1) {
            $this->parametersTypes = [];
            return;
        }
        foreach ($matches[0] as $typeHint) {
            $this->extractDeclarationFrom($typeHint);
        }
    }

    private function extractDeclarationFrom(string $typeHint): void
    {
        if (preg_match(self::PARAMETER_EXPRESSION, $typeHint, $match) === 1) {
            [$_, $type, $parameterName] = $match;
            $this->parametersTypes[$parameterName] = TypeDeclaration::from($type);
        }
    }
}
