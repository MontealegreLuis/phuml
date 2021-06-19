<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\Builders\Members\UnsupportedType;

final class TypeBuilder
{
    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromMethodParameter($type, MethodDocBlock $docBlock, string $name): TypeDeclaration
    {
        if ($type === null) {
            return $docBlock->typeOfParameter($name);
        }

        return $this->fromParsedType($type);
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromMethodReturnType($type, MethodDocBlock $docBlock): TypeDeclaration
    {
        if ($type === null) {
            return $docBlock->returnType();
        }

        return $this->fromParsedType($type);
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    private function fromParsedType($type): TypeDeclaration
    {
        if ($type instanceof NullableType) {
            return TypeDeclaration::fromNullable((string) $type->type);
        }

        if ($type instanceof Name) {
            return TypeDeclaration::from($type->getLast());
        }

        if ($type instanceof Identifier) {
            return TypeDeclaration::from((string) $type);
        }

        throw UnsupportedType::declaredAs($type);
    }
}
