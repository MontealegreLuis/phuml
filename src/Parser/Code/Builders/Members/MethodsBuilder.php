<?php
/**
 * PHP version 7.1
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
        return array_map(function (ClassMethod $method) {
            return $this->buildMethod($method);
        }, $this->runFilters($methods));
    }

    private function buildMethod(ClassMethod $method): Method
    {
        $name = $method->name;
        $modifier = $this->resolveVisibility($method);
        $comment = $method->getDocComment();
        $returnType = MethodDocBlock::from($comment)->returnType();
        $parameters = $this->buildParameters($method->params, $comment);
        switch (true) {
            case $method->isAbstract():
                return AbstractMethod::$modifier($name, $parameters, $returnType);
            case $method->isStatic():
                return StaticMethod::$modifier($name, $parameters, $returnType);
            default:
                return Method::$modifier($name, $parameters, $returnType);
        }
    }

    /**
     * @param Param[] $parameters
     * @return Variable[]
     */
    private function buildParameters(array $parameters, ?string $docBlock): array
    {
        return array_map(function (Param $parameter) use ($docBlock) {
            $name = "\${$parameter->name}";
            $type = $parameter->type;
            if ($type !== null) {
                if($type instanceof \PhpParser\Node\NullableType) {
                    $type = $type->type;
                }
                $typeDeclaration = TypeDeclaration::from($type);
            } else {
                $typeDeclaration = MethodDocBlock::from($docBlock)->typeOfParameter($name);
            }
            return Variable::declaredWith($name, $typeDeclaration);
        }, $parameters);
    }
}
