<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\AttributeDocBlock;
use PhUml\Code\Attributes\StaticAttribute;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

/**
 * It builds an array of `Attributes` for a `ClassDefinition` or a `TraitDefinition`
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
        $attributes = array_filter($definitionAttributes, static function ($attribute): bool {
            return $attribute instanceof Property;
        });

        return array_map(function (Property $attribute): Attribute {
            $name = "\${$attribute->props[0]->name}";
            $visibility = $this->resolveVisibility($attribute);
            $comment = $attribute->getDocComment() === null ? null : $attribute->getDocComment()->getText();
            $variable = Variable::declaredWith($name, $this->extractTypeFrom($comment));
            if ($attribute->isStatic()) {
                return new StaticAttribute($variable, $visibility);
            }
            return new Attribute($variable, $visibility);
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
