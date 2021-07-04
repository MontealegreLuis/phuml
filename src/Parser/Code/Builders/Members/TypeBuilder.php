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
        $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $methodDocBlock->typeOfParameter($name);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if ($typeDeclaration->isBuiltInArray() && $methodDocBlock->hasTypeOfParameter($name)) {
            return $methodDocBlock->typeOfParameter($name);
        }
        return $typeDeclaration;
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromMethodReturnType($type, ?Doc $docBlock): TypeDeclaration
    {
        $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $methodDocBlock->returnType();
        }

        $typeDeclaration = $this->fromParsedType($type);
        if ($typeDeclaration->isBuiltInArray() && $methodDocBlock->hasReturnType()) {
            return $methodDocBlock->returnType();
        }
        return $typeDeclaration;
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    public function fromAttributeType($type, ?Doc $docBlock): TypeDeclaration
    {
        $attributeDocBlock = new AttributeDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $attributeDocBlock->attributeType();
        }

        $typeDeclaration = $this->fromParsedType($type);
        if ($typeDeclaration->isBuiltInArray() && $attributeDocBlock->hasAttributeType()) {
            return $attributeDocBlock->attributeType();
        }
        return $typeDeclaration;
    }

    /** @param Identifier|Name|NullableType|UnionType|null $type */
    private function fromParsedType($type): TypeDeclaration
    {
        switch (true) {
            case $type instanceof NullableType:
                return TypeDeclaration::fromNullable((string) $type->type);
            case $type instanceof Name:
                return TypeDeclaration::from($type->getLast());
            case $type instanceof Identifier:
                return TypeDeclaration::from((string) $type);
            default:
                throw UnsupportedType::declaredAs($type);
        }
    }
}
