<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PHPUnit\Framework\TestCase;
use PhUml\Code\UseStatements;
use PhUml\TestBuilders\A;

final class ParametersBuilderTest extends TestCase
{
    /** @test */
    function it_parses_multiple_method_parameters()
    {
        $parsedParameters = [
            new Param(new Variable('page'), null, 'int', true),
            new Param(new Variable('size'), null, 'int'),
            new Param(new Variable('items'), null, 'int', false, true),
        ];
        $builder = new ParametersBuilder(A::typeBuilderBuilder()->build());

        $parameters = $builder->build($parsedParameters, null, new UseStatements([]));

        $this->assertEquals('&$page: int', $parameters[0]->__toString());
        $this->assertEquals('$size: int', $parameters[1]->__toString());
        $this->assertEquals('...$items: int', $parameters[2]->__toString());
    }
}
