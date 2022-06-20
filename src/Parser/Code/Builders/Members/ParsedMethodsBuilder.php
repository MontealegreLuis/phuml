<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\UseStatements;
use PhUml\Parser\Code\Builders\TagName;

/**
 * It builds an array with `Method`s for a `ClassDefinition`, an `InterfaceDefinition` or a
 * `TraitDefinition`
 */
final class ParsedMethodsBuilder implements MethodsBuilder
{
    public function __construct(
        private readonly ParametersBuilder $parametersBuilder,
        private readonly TypeBuilder $typeBuilder,
        private readonly VisibilityBuilder $visibilityBuilder,
    ) {
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods, UseStatements $useStatements): array
    {
        return array_map(
            fn (ClassMethod $method): Method => $this->buildMethod($method, $useStatements),
            $methods
        );
    }

    private function buildMethod(ClassMethod $method, UseStatements $useStatements): Method
    {
        $name = $method->name->name;
        $visibility = $this->visibilityBuilder->build($method);
        $docBlock = $method->getDocComment();
        $returnType = $this->typeBuilder->fromType($method->returnType, $docBlock, TagName::RETURN, $useStatements);
        $parameters = $this->parametersBuilder->build($method->params, $docBlock, $useStatements);
        return match (true) {
            $method->isAbstract() => new Method($name, $visibility, $returnType, $parameters, isAbstract: true),
            $method->isStatic() => new Method($name, $visibility, $returnType, $parameters, isStatic: true),
            default => new Method($name, $visibility, $returnType, $parameters),
        };
    }
}
