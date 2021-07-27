<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name as NodeName;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhUml\Code\Attributes\AttributeDocBlock;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Variables\TypeDeclaration;

final class TypeBuilder
{
    public function fromMethodParameter(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock,
        string $name
    ): TypeDeclaration {
        $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $methodDocBlock->typeOfParameter($name);
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }
        if (! $methodDocBlock->hasTypeOfParameter($name)) {
            return $typeDeclaration;
        }
        return $methodDocBlock->typeOfParameter($name);
    }

    public function fromMethodReturnType(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock
    ): TypeDeclaration {
        $methodDocBlock = new MethodDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $methodDocBlock->returnType();
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }
        if (! $methodDocBlock->hasReturnType()) {
            return $typeDeclaration;
        }
        return $methodDocBlock->returnType();
    }

    public function fromAttributeType(
        Identifier|Name|NullableType|UnionType|null $type,
        ?Doc $docBlock
    ): TypeDeclaration {
        $attributeDocBlock = new AttributeDocBlock($docBlock === null ? null : $docBlock->getText());
        if ($type === null) {
            return $attributeDocBlock->attributeType();
        }

        $typeDeclaration = $this->fromParsedType($type);
        if (! $typeDeclaration->isBuiltInArray()) {
            return $typeDeclaration;
        }
        if (! $attributeDocBlock->hasAttributeType()) {
            return $typeDeclaration;
        }
        return $attributeDocBlock->attributeType();
    }

    private function fromParsedType(Identifier|Name|NullableType|UnionType|null $type): TypeDeclaration
    {
        return match (true) {
            $type instanceof NullableType => TypeDeclaration::fromNullable((string) $type->type),
            $type instanceof Name => TypeDeclaration::from($type->getLast()),
            $type instanceof Identifier => TypeDeclaration::from((string) $type),
            $type === null => TypeDeclaration::absent(),
            default => TypeDeclaration::fromUnionType($this->fromUnionType($type)),
        };
    }

    /** @return string[] */
    private function fromUnionType(UnionType $type): array
    {
        return array_map(function (Identifier|NodeName $name): string {
            return $name instanceof Identifier ? $name->name : $name->getLast();
        }, $type->types);
    }
}
