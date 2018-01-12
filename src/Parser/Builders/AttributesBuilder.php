<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Builders;

use PhpParser\Node\Stmt\Property;

class AttributesBuilder
{
    public function build(array $classAttributes): array
    {
        $attributes = [];
        foreach ($classAttributes as $attribute) {
            if (!($attribute instanceof Property)) {
                continue;
            }
            $attributes[] = [
                "\${$attribute->props[0]->name}",
                $this->resolveVisibility($attribute),
                $attribute->getDocComment()
            ];
        }
        return $attributes;
    }

    /** @param Property $statement */
    private function resolveVisibility($statement): string
    {
        switch (true) {
            case $statement->isPublic():
                return 'public';
            case $statement->isPrivate():
                return 'private';
            default:
                return 'protected';
        }
    }
}
