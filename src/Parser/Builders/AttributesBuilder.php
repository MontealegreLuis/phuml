<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Builders;

use PhpParser\Node\Stmt\Property;

/**
 * It builds an array with the meta-information of a class attribute
 *
 * The generated array has the following structure
 *
 * - name
 * - visibility
 * - doc block
 */
class AttributesBuilder
{
    public function build(array $classAttributes): array
    {
        return array_map(function (Property $attribute) {
            return [
                "\${$attribute->props[0]->name}",
                $this->resolveVisibility($attribute),
                $attribute->getDocComment()
            ];
        }, array_filter($classAttributes, function ($attribute) {
            return $attribute instanceof Property;
        }));
    }

    private function resolveVisibility(Property $attribute): string
    {
        switch (true) {
            case $attribute->isPublic():
                return 'public';
            case $attribute->isPrivate():
                return 'private';
            default:
                return 'protected';
        }
    }
}
