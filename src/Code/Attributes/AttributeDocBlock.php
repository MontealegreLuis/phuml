<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Variables\TypeDeclaration;

/**
 * It creates a type declaration for an attribute from its `@var` tag
 */
final class AttributeDocBlock
{
    private const VAR_EXPRESSION = '/@var\s*([\w]+(\[\])?)/';

    private TypeDeclaration $attributeType;

    public function __construct(?string $comment)
    {
        $this->extractType($comment);
    }

    public function attributeType(): TypeDeclaration
    {
        return $this->attributeType;
    }

    public function hasAttributeType(): bool
    {
        return $this->attributeType->isPresent();
    }

    private function extractType(?string $comment): void
    {
        if (preg_match(self::VAR_EXPRESSION, (string) $comment, $matches) === 1) {
            $this->attributeType = TypeDeclaration::from(trim($matches[1]));
            return;
        }
        $this->attributeType = TypeDeclaration::absent();
    }
}
