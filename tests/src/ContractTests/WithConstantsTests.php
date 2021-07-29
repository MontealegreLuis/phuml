<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\Attributes\Constant;
use PhUml\Code\Attributes\HasConstants;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;

trait WithConstantsTests
{
    /** @test */
    function it_has_no_constants_by_default()
    {
        $noConstantsDefinition = $this->definitionWithConstants();

        $constants = $noConstantsDefinition->constants();

        $this->assertEmpty($constants);
    }

    /** @test */
    function it_knows_its_constants()
    {
        $constants = [
            new Constant('FIRST_CONSTANT', TypeDeclaration::absent(), Visibility::public()),
            new Constant('SECOND_CONSTANT', TypeDeclaration::from('string'), Visibility::public()),
        ];
        $definitionWithConstants = $this->definitionWithConstants($constants);

        $definitionConstants = $definitionWithConstants->constants();

        $this->assertEquals($constants, $definitionConstants);
    }

    /** @param Constant[] $constants */
    abstract protected function definitionWithConstants(array $constants = []): HasConstants;
}
