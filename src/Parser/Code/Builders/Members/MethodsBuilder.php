<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\Methods\MethodDocBlock;

/**
 * It builds an array with `Method`s for a `ClassDefinition`, an `InterfaceDefinition` or a
 * `TraitDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
class MethodsBuilder
{
    /** @var ParametersBuilder */
    private $parametersBuilder;

    /** @var VisibilityBuilder */
    private $visibilityBuilder;

    /** @var TypeBuilder */
    private $typeBuilder;

    /** @var VisibilityFilters */
    private $visibilityFilters;

    public function __construct(
        ParametersBuilder $parametersBuilder,
        TypeBuilder $typeBuilder,
        VisibilityBuilder $visibilityBuilder,
        VisibilityFilters $filters
    ) {
        $this->visibilityFilters = $filters;
        $this->typeBuilder = $typeBuilder;
        $this->parametersBuilder = $parametersBuilder;
        $this->visibilityBuilder = $visibilityBuilder;
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods): array
    {
        return array_map(function (ClassMethod $method): Method {
            return $this->buildMethod($method);
        }, $this->visibilityFilters->apply($methods));
    }

    private function buildMethod(ClassMethod $method): Method
    {
        $name = $method->name->name;
        $visibility = $this->visibilityBuilder->build($method);
        $docBlock = $method->getDocComment() === null ? null : $method->getDocComment()->getText();
        $methodDocBlock = MethodDocBlock::from($docBlock);
        $returnType = $this->typeBuilder->fromMethodReturnType($method->returnType, $methodDocBlock);
        $parameters = $this->parametersBuilder->build($method->params, $methodDocBlock);
        switch (true) {
            case $method->isAbstract():
                return new Method($name, $visibility, $returnType, $parameters, true);
            case $method->isStatic():
                return new Method($name, $visibility, $returnType, $parameters, false, true);
            default:
                return new Method($name, $visibility, $returnType, $parameters);
        }
    }
}
