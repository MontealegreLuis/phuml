<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;

final class ClassVisitorTest extends TestCase
{
    /** @test */
    function it_ignores_anonymous_classes()
    {
        $builder = $this->prophesize(ClassDefinitionBuilder::class);
        $codebase = new Codebase();
        $visitor = new ClassVisitor($builder->reveal(), $codebase);
        $anonymousClass = new Class_(null);

        $visitor->leaveNode($anonymousClass);

        $builder->build($anonymousClass)->shouldNotHaveBeenCalled();
        $this->assertEmpty($codebase->definitions());
    }
}
