<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use phpDocumentor\Reflection\DocBlock\Tags\InvalidTag;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\TagWithType;

/* It finds a parameter tag by parameter name **/
final class ParameterTagFilterFactory
{
    public function filter(string $parameterName): callable
    {
        return static fn (TagWithType|InvalidTag $parameter)
            => $parameter instanceof Param && "\${$parameter->getVariableName()}" === $parameterName;
    }
}
