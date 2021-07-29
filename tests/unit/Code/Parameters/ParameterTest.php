<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Parameters;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Variables\HasType;
use PhUml\ContractTests\WithTypeDeclarationTests;
use PhUml\TestBuilders\A;

final class ParameterTest extends TestCase
{
    use WithTypeDeclarationTests;

    /** @test */
    function it_can_be_represented_as_string()
    {
        $parameter = new Parameter(A::variable('$parameter')->build());
        $variadicParameter = new Parameter(
            A::variable('$variadicParameter')->withType('string')->build(),
            true
        );
        $byReferenceParameter = new Parameter(
            A::variable('$byReferenceParameter')->withType('array')->build(),
            false,
            true
        );

        $this->assertEquals('$parameter', $parameter->__toString());
        $this->assertEquals('...$variadicParameter: string', $variadicParameter->__toString());
        $this->assertEquals('&$byReferenceParameter: array', $byReferenceParameter->__toString());
    }

    protected function memberWithoutType(): HasType
    {
        return new Parameter(A::variable('$parameter')->build());
    }

    protected function typeDeclaration(): HasType
    {
        return new Parameter(A::variable('$parameter')->withType('Directory')->build());
    }

    protected function memberWithBuiltInType(): HasType
    {
        return new Parameter(A::variable('$parameter')->withType('string')->build());
    }
}
