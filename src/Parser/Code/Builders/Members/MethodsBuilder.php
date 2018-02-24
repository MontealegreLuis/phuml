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
 * It builds an array with `Method`s for either a `ClassDefinition` or an `InterfaceDefinition`
 *
 * It can run one or more `VisibilityFilter`s
 *
 * @see PrivateVisibilityFilter
 * @see ProtectedVisibilityFilter
 */
class MethodsBuilder extends FiltersRunner
{
    /**
     * @param ClassMethod[] $classMethods
     * @return Method[]
     */
    public function build(array $classMethods): array
    {
        return array_map(function (ClassMethod $method) {
            return $this->buildMethod($method);
        }, $this->runFilters($classMethods));
    }

    private function buildMethod(ClassMethod $method): Method
    {
        $name = $method->name;
        $modifier = $this->resolveVisibility($method);
        $comment = $method->getDocComment();
        $returnType = MethodDocBlock::from($comment)->returnType();
        $parameters = $method->params;
        if ($method->isAbstract()) {
            return AbstractMethod::$modifier($name, $this->buildParameters($parameters, $comment), $returnType);
        }
        if ($method->isStatic()) {
            return StaticMethod::$modifier($name, $this->buildParameters($parameters, $comment), $returnType);
        }
        return Method::$modifier($name, $this->buildParameters($parameters, $comment), $returnType);
    }

    private function buildParameters(array $parameters, ?string $docBlock): array
    {
        return array_map(function (Param $parameter) use ($docBlock) {
            $name = "\${$parameter->name}";
            $type = $parameter->type;
            if ($type !== null) {
                $typeDeclaration = TypeDeclaration::from($type);
            } else {
                $typeDeclaration = MethodDocBlock::from($docBlock)->typeOfParameter($name);
            }
            return Variable::declaredWith($name, $typeDeclaration);
        }, $parameters);
    }
}
