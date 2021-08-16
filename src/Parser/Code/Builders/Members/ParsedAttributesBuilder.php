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
use PhUml\Code\UseStatements;
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
final class ParsedAttributesBuilder implements AttributesBuilder
{
    public function __construct(
        private VisibilityBuilder $visibilityBuilder,
        private TypeBuilder $typeBuilder,
    ) {
    }

    /**
     * @param Property[] $parsedAttributes
     * @return Attribute[]
     */
    public function build(array $parsedAttributes, UseStatements $useStatements): array
    {
        return array_map(function (Property $attribute) use ($useStatements): Attribute {
            $variable = new Variable(
                "\${$attribute->props[0]->name}",
                $this->typeBuilder->fromAttributeType($attribute->type, $attribute->getDocComment(), $useStatements)
            );
            $visibility = $this->visibilityBuilder->build($attribute);

            return new Attribute($variable, $visibility, $attribute->isStatic());
        }, $parsedAttributes);
    }

    /**
     * @param Node\Param[] $promotedProperties
     * @return Attribute[]
     */
    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array
    {
        return array_map(function (Node\Param $param) use ($useStatements): Attribute {
            /** @var Node\Expr\Variable $var */
            $var = $param->var;

            /** @var string $name */
            $name = $var->name;

            $type = $this->typeBuilder->fromMethodParameter(
                $param->type,
                $param->getDocComment(),
                $name,
                $useStatements
            );
            $visibility = $this->visibilityBuilder->fromFlags($param->flags);

            return new Attribute(new Variable("\$${name}", $type), $visibility);
        }, $promotedProperties);
    }
}
