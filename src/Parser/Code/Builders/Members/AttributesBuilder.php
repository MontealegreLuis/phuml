<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\AttributeDocBlock;
use PhUml\Code\Attributes\StaticAttribute;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

/**
 * It builds an array of `Attributes` for a `ClassDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
class AttributesBuilder extends FiltersRunner
{
    /**
     * @param \PhpParser\Node[] $definitionAttributes
     * @return Attribute[]
     */
    public function build(array $definitionAttributes): array
    {
        $attributes = array_filter($definitionAttributes, function ($attribute) {
            return $attribute instanceof Property;
        });

        return array_map(function (Property $attribute) {
            $name = "\${$attribute->props[0]->name}";
            $modifier = $this->resolveVisibility($attribute);
            $comment = $attribute->getDocComment();
            if ($attribute->isStatic()) {
                return StaticAttribute::$modifier($name, $this->extractTypeFrom($comment));
            }
            return Attribute::$modifier($name, $this->extractTypeFrom($comment));
        }, $this->runFilters($attributes));
    }

    private function extractTypeFrom(?string $comment): TypeDeclaration
    {
        if ($comment === null) {
            return TypeDeclaration::absent();
        }

        return AttributeDocBlock::from($comment)->extractType();
    }
}
