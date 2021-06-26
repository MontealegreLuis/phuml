<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Param;
use PhUml\Code\Methods\MethodDocBlock;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\Variables\Variable;

final class ParametersBuilder
{
    /** @var TypeBuilder */
    private $typeBuilder;

    public function __construct(TypeBuilder $typeBuilder)
    {
        $this->typeBuilder = $typeBuilder;
    }

    /**
     * @param Param[] $parameters
     * @return Parameter[]
     */
    public function build(array $parameters, MethodDocBlock $methodDocBlock): array
    {
        return array_map(function (Param $parameter) use ($methodDocBlock): Parameter {
            /** @var \PhpParser\Node\Expr\Variable $parsedParameter Since the parser throws error by default */
            $parsedParameter = $parameter->var;

            /** @var string $parameterName Since it's a parameter not a variable */
            $parameterName = $parsedParameter->name;

            $name = "\${$parameterName}";
            $type = $parameter->type;

            $typeDeclaration = $this->typeBuilder->fromMethodParameter($type, $methodDocBlock, $name);

            return new Parameter(new Variable($name, $typeDeclaration), $parameter->variadic, $parameter->byRef);
        }, $parameters);
    }
}
