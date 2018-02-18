<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\AttributeDocBlock;
use PhUml\Code\Attributes\StaticAttribute;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Raw\Builders\Filters\PrivateMembersFilter;
use PhUml\Parser\Raw\Builders\Filters\ProtectedMembersFilter;

/**
 * It builds an array with the meta-information of a class attribute
 *
 * The generated array has the following structure
 *
 * - name
 * - visibility
 * - doc block
 *
 * You can run one or more filters, the current available filters will exclude
 *
 * - protected attributes
 * - private attributes
 * - both protected and private if both filters are provided
 *
 * @see PrivateMembersFilter
 * @see ProtectedMembersFilter
 */
class AttributesBuilder extends MembersBuilder
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
