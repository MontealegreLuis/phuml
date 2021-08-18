<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPUnit\Framework\TestCase;

final class ParsedConstantsBuilderTest extends TestCase
{
    /** @test */
    function it_parses_a_class_constants()
    {
        $constants = [
            new ClassConst([new Const_('INTEGER', new LNumber(1))]),
            new ClassConst([new Const_('FLOAT', new DNumber(1.5))], Class_::MODIFIER_PRIVATE),
            new ClassConst([new Const_('STRING', new String_('test'))], Class_::MODIFIER_PROTECTED),
            new ClassConst([new Const_('IS_TRUE', new ConstFetch(new Name(['false'])))]),
            new ClassConst([new Const_('IS_FALSE', new ConstFetch(new Name(['true'])))]),
        ];
        $builder = new ParsedConstantsBuilder(new VisibilityBuilder());

        $constants = $builder->build($constants);

        $this->assertCount(5, $constants);
        $this->assertEquals('+INTEGER: int', (string) $constants[0]);
        $this->assertEquals('-FLOAT: float', (string) $constants[1]);
        $this->assertEquals('#STRING: string', (string) $constants[2]);
        $this->assertEquals('+IS_TRUE: bool', (string) $constants[3]);
        $this->assertEquals('+IS_FALSE: bool', (string) $constants[4]);
    }

    /** @test */
    function it_does_not_extract_types_for_expressions()
    {
        $parsedConstants = [
            // const GREETING = 'My sentence' . PHP_EOL;
            new ClassConst([new Const_(
                'GREETING',
                new Concat(
                    new String_('My sentence'),
                    new ConstFetch(new Name('PHP_EOL'))
                )
            )]),
            // const IS_GREATER = 1 > 0;
            new ClassConst([new Const_(
                'IS_GREATER',
                new Greater(
                    new LNumber(1),
                    new LNumber(0)
                )
            )]),
        ];
        $builder = new ParsedConstantsBuilder(new VisibilityBuilder());

        $constants = $builder->build($parsedConstants);

        $this->assertCount(2, $constants);
        $this->assertEquals('+GREETING', (string) $constants[0]);
        $this->assertEquals('+IS_GREATER', (string) $constants[1]);
    }
}
