<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Comment\Doc;
use PhpParser\Node\Param;
use PhUml\Code\Parameters\Parameter;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\Variable;
use PhUml\Parser\Code\Builders\ParameterTagFilterFactory;
use PhUml\Parser\Code\Builders\TagName;

final class ParametersBuilder
{
    public function __construct(
        private readonly TypeBuilder $typeBuilder,
        private readonly ParameterTagFilterFactory $filterFactory
    ) {
    }

    /**
     * @param Param[] $parameters
     * @return Parameter[]
     */
    public function build(array $parameters, ?Doc $methodDocBlock, UseStatements $useStatements): array
    {
        return array_map(function (Param $parameter) use ($methodDocBlock, $useStatements): Parameter {
            /** @var \PhpParser\Node\Expr\Variable $parsedParameter Since the parser throws error by default */
            $parsedParameter = $parameter->var;

            /** @var string $parameterName Since it's a parameter not a variable */
            $parameterName = $parsedParameter->name;

            $name = "\${$parameterName}";
            $type = $parameter->type;

            $typeDeclaration = $this->typeBuilder->fromType(
                $type,
                $methodDocBlock,
                TagName::PARAM,
                $useStatements,
                $this->filterFactory->filter($name)
            );

            return new Parameter(new Variable($name, $typeDeclaration), $parameter->variadic, $parameter->byRef);
        }, $parameters);
    }
}
