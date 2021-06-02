<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Visitors;

use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\Codebase;
use PhUml\Parser\Code\Builders\ClassDefinitionBuilder;
use Prophecy\Argument;

class ClassVisitorTest extends TestCase
{
    /** @test */
    function it_ignores_anonymous_classes()
    {
        $builder = $this->prophesize(ClassDefinitionBuilder::class);
        $codebase = $this->prophesize(Codebase::class);
        $visitor = new ClassVisitor($builder->reveal(), $codebase->reveal());
        $anonymousClass = new Class_(null);

        $visitor->leaveNode($anonymousClass);

        $builder->build($anonymousClass)->shouldNotHaveBeenCalled();
        $codebase->add(Argument::type(ClassDefinition::class))->shouldNotHaveBeenCalled();
    }
}
