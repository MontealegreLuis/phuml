<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhUml\Code\Attributes\AttributeDocBlock;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Variables\TypeDeclaration;

final class TypeBuilder
{
    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromMethodParameter($type, ?Doc $docBlock, string $name): TypeDeclaration
    {
        if ($type === null) {
            $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
            return $methodDocBlock->typeOfParameter($name);
        }

        return $this->fromParsedType($type);
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromMethodReturnType($type, ?Doc $docBlock): TypeDeclaration
    {
        if ($type === null) {
            $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
            return $methodDocBlock->returnType();
        }

        return $this->fromParsedType($type);
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromAttributeType($type, ?Doc $docBlock): TypeDeclaration
    {
        if ($type === null) {
            $attributeDocBlock = new AttributeDocBlock($docBlock === null ? null : $docBlock->getText());
            return $attributeDocBlock->attributeType();
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
