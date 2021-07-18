<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Variables\Variable;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

/**
 * It builds an array of `Attributes` for a `ClassDefinition` or a `TraitDefinition`
 *
 * It applies one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
final class FilteredAttributesBuilder implements AttributesBuilder
{
    public function __construct(
        private VisibilityBuilder $visibilityBuilder,
        private TypeBuilder $typeBuilder,
        private VisibilityFilters $visibilityFilters
    ) {
    }

    /**
     * @param Node[] $parsedAttributes
     * @return Attribute[]
     */
    public function build(array $parsedAttributes): array
    {
        $attributes = array_filter($parsedAttributes, static fn ($attribute): bool => $attribute instanceof Property);

        return array_map(function (Property $attribute): Attribute {
            $variable = new Variable(
                "\${$attribute->props[0]->name}",
                $this->typeBuilder->fromAttributeType($attribute->type, $attribute->getDocComment())
            );
            $visibility = $this->visibilityBuilder->build($attribute);

            return new Attribute($variable, $visibility, $attribute->isStatic());
        }, $this->visibilityFilters->apply($attributes));
    }
}
