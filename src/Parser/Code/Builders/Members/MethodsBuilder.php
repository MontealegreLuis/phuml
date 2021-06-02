<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhUml\Code\Methods\AbstractMethod;
use PhUml\Code\Methods\Method;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Methods\StaticMethod;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

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
        $name = $method->name;
        $visibility = $this->resolveVisibility($method);
        $docBlock = $method->getDocComment();
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
        return array_map(static function (Param $parameter) use ($docBlock): Variable {
            /** @var \PhpParser\Node\Expr\Variable $parsedParameter Since the parser throws error by default */
            $parsedParameter = $parameter->var;
            /** @var string $parameterName Since it's a parameter not a variable */
            $parameterName = $parsedParameter->name;
            $name = "\${$parameterName}";

            $type = $parameter->type;
            if ($type === null) {
                $typeDeclaration = MethodDocBlock::from($docBlock)->typeOfParameter($name);
            } elseif ($type instanceof NullableType) {
                $typeDeclaration = TypeDeclaration::fromNullable($type->type);
            } elseif ($type instanceof Name) {
                $typeDeclaration = TypeDeclaration::from($type->getLast());
            } elseif ($type instanceof Identifier) {
                $typeDeclaration = TypeDeclaration::from((string)$type);
            } else {
                throw UnsupportedType::declaredAs($type);
            }

            return Variable::declaredWith($name, $typeDeclaration);
        }, $parameters);
    }

    private function extractReturnType(ClassMethod $method, ?string $docBlock): TypeDeclaration
    {
        if ($method->returnType instanceof NullableType) {
            return TypeDeclaration::fromNullable((string)$method->returnType->type);
        }
        if ($method->returnType === null) {
            return MethodDocBlock::from($docBlock)->returnType();
        }
        if ($method->returnType instanceof Identifier) {
            return TypeDeclaration::from((string)$method->returnType);
        }
        if ($method->returnType instanceof Name) {
            return TypeDeclaration::from($method->returnType->getLast());
        }

        throw UnsupportedType::declaredAs($method->returnType);
    }
}
