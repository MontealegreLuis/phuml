<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node;
use PhpParser\Node\Stmt\Property as ParsedProperty;
use PhUml\Code\Properties\Property;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\Variable;

/**
 * It builds an array of `Property` for a `ClassDefinition` or a `TraitDefinition`
 */
final class ParsedPropertiesBuilder implements PropertiesBuilder
{
    public function __construct(
        private readonly VisibilityBuilder $visibilityBuilder,
        private readonly TypeBuilder $typeBuilder,
    ) {
    }

    /**
     * @param ParsedProperty[] $parsedProperties
     * @return Property[]
     */
    public function build(array $parsedProperties, UseStatements $useStatements): array
    {
        return array_map(function (ParsedProperty $property) use ($useStatements): Property {
            $variable = new Variable(
                "\${$property->props[0]->name}",
                $this->typeBuilder->fromPropertyType($property->type, $property->getDocComment(), $useStatements)
            );
            $visibility = $this->visibilityBuilder->build($property);

            return new Property($variable, $visibility, $property->isStatic());
        }, $parsedProperties);
    }

    /**
     * @param Node\Param[] $promotedProperties
     * @return Property[]
     */
    public function fromPromotedProperties(array $promotedProperties, UseStatements $useStatements): array
    {
        return array_map(function (Node\Param $param) use ($useStatements): Property {
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

            return new Property(new Variable("\$${name}", $type), $visibility);
        }, $promotedProperties);
    }
}
