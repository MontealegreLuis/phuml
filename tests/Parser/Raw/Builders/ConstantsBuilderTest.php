<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassConst;
use PHPUnit\Framework\TestCase;

class ConstantsBuilderTest extends TestCase
{
    /** @test */
    function it_parses_a_class_constants()
    {
        $constants = [
            new ClassConst([new Const_('INTEGER', new LNumber(1))]),
            new ClassConst([new Const_('FLOAT', new DNumber(1.5))]),
            new ClassConst([new Const_('STRING', new String_('test'))]),
            new ClassConst([new Const_('BOOLEAN', new ConstFetch(new Name(['false'])))]),
        ];
        $builder = new ConstantsBuilder();

        $rawConstants = $builder->build($constants);

        $this->assertCount(4, $rawConstants);
        $this->assertEquals('INTEGER', $rawConstants[0][0]);
        $this->assertEquals('int', $rawConstants[0][1]);
        $this->assertEquals('FLOAT', $rawConstants[1][0]);
        $this->assertEquals('float', $rawConstants[1][1]);
        $this->assertEquals('STRING', $rawConstants[2][0]);
        $this->assertEquals('string', $rawConstants[2][1]);
        $this->assertEquals('BOOLEAN', $rawConstants[3][0]);
        $this->assertEquals('bool', $rawConstants[3][1]);
    }

    /** @test */
    function it_does_not_extracts_types_for_expressions()
    {
        // const GREETING = 'My sentence' . PHP_EOL;
        $constants = [
            new ClassConst([new Const_(
                'GREETING',
                new Concat(
                    new String_('My sentence'),
                    new ConstFetch(new Name('PHP_EOL'))
                )
            )]),
        ];
        $builder = new ConstantsBuilder();

        $rawConstants = $builder->build($constants);

        $this->assertCount(1, $rawConstants);
        $this->assertEquals('GREETING', $rawConstants[0][0]);
        $this->assertNull($rawConstants[0][1]);
    }
}
