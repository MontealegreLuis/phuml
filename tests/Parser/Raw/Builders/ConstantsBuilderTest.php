<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Raw\Builders;

use PhpParser\Node\Const_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\ClassConst;
use PHPUnit\Framework\TestCase;

class ConstantsBuilderTest extends TestCase
{
    /** @test */
    function it_parses_class_constants()
    {
        $constants = [
            new ClassConst([new Const_('CONSTANT_NAME', new LNumber(1))])
        ];

        $builder = new ConstantsBuilder();

        $rawConstants = $builder->build($constants);

        $this->assertCount(1, $rawConstants);
        $this->assertEquals('CONSTANT_NAME', $rawConstants[0][0]);
    }
}
