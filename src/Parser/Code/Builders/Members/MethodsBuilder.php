<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\AbstractMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Methods\StaticMethod;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;
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
    /** @var TypeBuilder */
    private $typeBuilder;

    public function __construct(TypeBuilder $typeBuilder, array $filters = [])
    {
        parent::__construct($filters);
        $this->typeBuilder = $typeBuilder;
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
        $returnType = $this->extractReturnType($method, $docBlock);
        $parameters = $this->buildParameters($method->params, $docBlock);
        switch (true) {
            case $method->isAbstract():
                return new AbstractMethod($name, $visibility, $returnType, $parameters);
            case $method->isStatic():
                return new StaticMethod($name, $visibility, $returnType, $parameters);
            default:
                return new Method($name, $visibility, $returnType, $parameters);
        }
    }

    /**
     * @param Param[] $parameters
     * @return Variable[]
     */
    private function buildParameters(array $parameters, ?string $docBlock): array
    {
        return array_map(function (Param $parameter) use ($docBlock): Variable {
            /** @var \PhpParser\Node\Expr\Variable $parsedParameter Since the parser throws error by default */
            $parsedParameter = $parameter->var;

            /** @var string $parameterName Since it's a parameter not a variable */
            $parameterName = $parsedParameter->name;

            $name = "\${$parameterName}";
            $methodDocBlock = MethodDocBlock::from($docBlock);
            $type = $parameter->type;

            $typeDeclaration = $this->typeBuilder->fromMethodParameter($type, $methodDocBlock, $name);

            return Variable::declaredWith($name, $typeDeclaration);
        }, $parameters);
    }

    private function extractReturnType(ClassMethod $method, ?string $docBlock): TypeDeclaration
    {
        $returnType = $method->returnType;
        $methodDocBlock = MethodDocBlock::from($docBlock);

        return $this->typeBuilder->fromMethodReturnType($returnType, $methodDocBlock);
    }
}
