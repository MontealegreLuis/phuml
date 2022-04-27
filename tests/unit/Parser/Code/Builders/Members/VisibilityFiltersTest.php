<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use PhpParser\Node\Const_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PHPUnit\Framework\TestCase;
use PhUml\Parser\Code\Builders\Filters\PrivateVisibilityFilter;
use PhUml\Parser\Code\Builders\Filters\ProtectedVisibilityFilter;

final class VisibilityFiltersTest extends TestCase
{
    /** @test */
    function it_excludes_private_properties()
    {
        $filters = new VisibilityFilters([new PrivateVisibilityFilter()]);

        $properties = $filters->apply($this->properties);

        $this->assertCount(4, $properties);
        $this->assertTrue($properties[1]->isPublic());
        $this->assertTrue($properties[2]->isPublic());
        $this->assertTrue($properties[4]->isProtected());
        $this->assertTrue($properties[5]->isProtected());
    }

    /** @test */
    function it_excludes_protected_properties()
    {
        $filters = new VisibilityFilters([new ProtectedVisibilityFilter()]);

        $properties = $filters->apply($this->properties);

        $this->assertCount(5, $properties);
        $this->assertTrue($properties[0]->isPrivate());
        $this->assertTrue($properties[1]->isPublic());
        $this->assertTrue($properties[2]->isPublic());
        $this->assertTrue($properties[3]->isPrivate());
        $this->assertTrue($properties[6]->isPrivate());
    }

    /** @test */
    function it_excludes_both_protected_and_private_properties()
    {
        $filters = new VisibilityFilters([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]);

        $properties = $filters->apply($this->properties);

        $this->assertCount(2, $properties);
        $this->assertTrue($properties[1]->isPublic());
        $this->assertTrue($properties[2]->isPublic());
    }

    /** @test */
    function it_excludes_private_methods()
    {
        $methodsBuilder = new VisibilityFilters([new PrivateVisibilityFilter()]);

        $methods = $methodsBuilder->apply($this->methods);

        $this->assertCount(4, $methods);
        $this->assertTrue($methods[1]->isPublic());
        $this->assertTrue($methods[2]->isPublic());
        $this->assertTrue($methods[4]->isProtected());
        $this->assertTrue($methods[5]->isProtected());
    }

    /** @test */
    function it_excludes_protected_methods()
    {
        $builder = new VisibilityFilters([new ProtectedVisibilityFilter()]);

        $methods = $builder->apply($this->methods);

        $this->assertCount(5, $methods);
        $this->assertTrue($methods[0]->isPrivate());
        $this->assertTrue($methods[1]->isPublic());
        $this->assertTrue($methods[2]->isPublic());
        $this->assertTrue($methods[3]->isPrivate());
        $this->assertTrue($methods[6]->isPrivate());
    }

    /** @test */
    function it_excludes_both_protected_and_private_methods()
    {
        $builder = new VisibilityFilters([new PrivateVisibilityFilter(), new ProtectedVisibilityFilter()]);

        $methods = $builder->apply($this->methods);

        $this->assertCount(2, $methods);
        $this->assertTrue($methods[1]->isPublic());
        $this->assertTrue($methods[2]->isPublic());
    }

    /** @test */
    function it_excludes_private_constants()
    {
        $filters = new VisibilityFilters([new PrivateVisibilityFilter()]);

        $constants = $filters->apply($this->constants);

        $this->assertCount(2, $constants);
        $this->assertTrue($constants[0]->isPublic());
        $this->assertTrue($constants[2]->isProtected()); // filters preserve original index
    }

    /** @test */
    function it_excludes_protected_constants()
    {
        $filters = new VisibilityFilters([new ProtectedVisibilityFilter()]);

        $constants = $filters->apply($this->constants);

        $this->assertCount(2, $constants);
        $this->assertTrue($constants[0]->isPublic());
        $this->assertTrue($constants[1]->isPrivate());
    }

    /** @before */
    function let()
    {
        $this->properties = [
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('willBeRemoved')]),
            new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('publicA')]),
            new Property(Class_::MODIFIER_PUBLIC, [new PropertyProperty('publicB')]),
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('willBeRemovedToo')]),
            new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedA')]),
            new Property(Class_::MODIFIER_PROTECTED, [new PropertyProperty('protectedB')]),
            new Property(Class_::MODIFIER_PRIVATE, [new PropertyProperty('toBeRemoved')]),
        ];
        $this->methods = [
            new ClassMethod('privateMethodA', ['type' => Class_::MODIFIER_PRIVATE]),
            new ClassMethod('publicMethodA', [
                'type' => Class_::MODIFIER_PUBLIC,
                'returnType' => new Name('AClassName'),
            ]),
            new ClassMethod('publicMethodA', [
                'type' => Class_::MODIFIER_PUBLIC | Class_::MODIFIER_STATIC,
            ]),
            new ClassMethod('privateMethodB', [
                'flags' => Class_::MODIFIER_PRIVATE | Class_::MODIFIER_ABSTRACT,
            ]),
            new ClassMethod('protectedMethodA', [
                'type' => Class_::MODIFIER_PROTECTED,
                'returnType' => new NullableType('int'),
            ]),
            new ClassMethod('protectedMethodB', [
                'type' => Class_::MODIFIER_PROTECTED,
                'returnType' => new Identifier('Method'),
            ]),
            new ClassMethod('privateMethodC', [
                'type' => Class_::MODIFIER_PRIVATE,
                'params'  => [
                    new Param(
                        new Variable('nullableParameter'),
                        null,
                        new NullableType('int')
                    ),
                ],
                'returnType' => 'string',
            ]),
        ];
        $this->constants = [
            new ClassConst([new Const_('INTEGER', new LNumber(1))]),
            new ClassConst([new Const_('FLOAT', new DNumber(1.5))], Class_::MODIFIER_PRIVATE),
            new ClassConst([new Const_('STRING', new String_('test'))], Class_::MODIFIER_PROTECTED),
        ];
    }

    /** @var Property[]  */
    private array $properties;

    /** @var ClassConst[] */
    private array $constants;

    /** @var ClassMethod[] */
    private array $methods;
}
