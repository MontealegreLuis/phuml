<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\AbstractMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Methods\StaticMethod;
use PhUml\Parser\Code\Builders\TypeBuilder;

/**
 * It builds an array with `Method`s for a `ClassDefinition`, an `InterfaceDefinition` or a
 * `TraitDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
class MethodsBuilder extends FiltersRunner
{
    /** @var ParametersBuilder */
    private $parametersBuilder;

    /** @var TypeBuilder */
    private $typeBuilder;

    public function __construct(ParametersBuilder $parametersBuilder, TypeBuilder $typeBuilder, array $filters = [])
    {
        parent::__construct($filters);
        $this->typeBuilder = $typeBuilder;
        $this->parametersBuilder = $parametersBuilder;
    }

    /**
     * @param ClassMethod[] $methods
     * @return Method[]
     */
    public function build(array $methods): array
    {
        return array_map(function (ClassMethod $method): Method {
            return $this->buildMethod($method);
        }, $this->runFilters($methods));
    }

    private function buildMethod(ClassMethod $method): Method
    {
        $name = $method->name->name;
        $visibility = $this->resolveVisibility($method);
        $docBlock = $method->getDocComment() === null ? null : $method->getDocComment()->getText();
        $methodDocBlock = MethodDocBlock::from($docBlock);
        $returnType = $this->typeBuilder->fromMethodReturnType($method->returnType, $methodDocBlock);
        $parameters = $this->parametersBuilder->build($method->params, $methodDocBlock);
        switch (true) {
            case $method->isAbstract():
                return new AbstractMethod($name, $visibility, $returnType, $parameters);
            case $method->isStatic():
                return new StaticMethod($name, $visibility, $returnType, $parameters);
            default:
                return new Method($name, $visibility, $returnType, $parameters);
        }
    }
}
