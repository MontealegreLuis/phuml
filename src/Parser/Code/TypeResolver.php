<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\Builders\TagType;
use PhUml\Parser\Code\Builders\TagTypeFactory;

final class TypeResolver
{
    public function __construct(private TagTypeFactory $factory)
    {
    }

    public function resolveForParameter(
        ?string $methodComment,
        string $name,
        UseStatements $useStatements
    ): TypeDeclaration {
        if ($methodComment === null) {
            return TypeDeclaration::absent();
        }

        $parameterType = $this->factory->parameterTypeFrom($methodComment, $name);

        return $parameterType instanceof TagType ? $parameterType->resolve($useStatements) : TypeDeclaration::absent();
    }

    public function resolveForReturn(?string $methodComment, UseStatements $useStatements): TypeDeclaration
    {
        if ($methodComment === null) {
            return TypeDeclaration::absent();
        }

        $returnType = $this->factory->returnTypeFrom($methodComment);

        return $returnType instanceof TagType ? $returnType->resolve($useStatements) : TypeDeclaration::absent();
    }

    public function resolveForAttribute(?string $attributeComment, UseStatements $useStatements): TypeDeclaration
    {
        if ($attributeComment === null) {
            return TypeDeclaration::absent();
        }

        $attributeType = $this->factory->attributeTypeFrom($attributeComment);

        return $attributeType instanceof TagType ? $attributeType->resolve($useStatements) : TypeDeclaration::absent();
    }
}
