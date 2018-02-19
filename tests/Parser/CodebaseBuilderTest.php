<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\Constant;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Methods\Method;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Parser\Raw\ExternalDefinitionsResolver;
use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\TestBuilders\A;

class CodebaseBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_interface_definition()
    {
        $builder = new CodebaseBuilder();
        $definitions = new RawDefinitions();
        $definitions->add(RawDefinition::interface(['interface' => 'ParentInterface']));
        $definitions->add(RawDefinition::interface(['interface' => 'AnotherParentInterface']));
        $definitions->add(RawDefinition::interface([
            'interface' => 'InterfaceName',
            'constants' => [
                new Constant('A_CONSTANT'),
                new Constant('TYPED_CONSTANT', TypeDeclaration::from('float'))
            ],
            'methods' => [
                Method::public('doSomething'),
                Method::public('changeThing', [
                    A::parameter('$name')->withType('string')->build(),
                ]),
            ],
            'extends' => ['ParentInterface', 'AnotherParentInterface'],
        ]));

        $codebase = $builder->buildFrom($definitions);

        $this->assertEquals(A::interface('InterfaceName')
            ->withAConstant('A_CONSTANT')
            ->withAConstant('TYPED_CONSTANT', 'float')
            ->withAPublicMethod('doSomething')
            ->withAPublicMethod('changeThing', A::parameter('$name')->withType('string')->build())
            ->extending(
                (new InterfaceDefinition('ParentInterface'))->name(),
                (new InterfaceDefinition('AnotherParentInterface'))->name()
            )
            ->build(),
            $codebase->get('InterfaceName')
        );
    }

    /** @test */
    function it_builds_a_class_definition()
    {
        $builder = new CodebaseBuilder();
        $definitions = new RawDefinitions();
        $definitions->add(RawDefinition::interface(['interface' => 'FirstInterface']));
        $definitions->add(RawDefinition::interface(['interface' => 'SecondInterface']));
        $definitions->add(RawDefinition::class(['class' => 'ParentClass']));
        $definitions->add(RawDefinition::class([
            'class' => 'ClassName',
            'constants' => [
                new Constant('A_CONSTANT'),
                new Constant('TYPED_CONSTANT', TypeDeclaration::from('float'))
            ],
            'attributes' => [
                Attribute::protected('$name'),
                Attribute::private('$age', TypeDeclaration::from('int')),
                Attribute::public('$phoneNumbers', TypeDeclaration::from('string[]')),
            ],
            'methods' => [
                Method::public('getAge', [], TypeDeclaration::from('int')),
                Method::public('changeThing', [
                    A::parameter('$name')->withType('string')->build(),
                ]),
            ],
            'implements' => [
                'FirstInterface',
                'SecondInterface',
            ],
            'extends' => 'ParentClass',
        ]));

        $codebase = $builder->buildFrom($definitions);

        $this->assertEquals(
            A::class('ClassName')
                ->withAConstant('A_CONSTANT')
                ->withAConstant('TYPED_CONSTANT', 'float')
                ->withAProtectedAttribute('$name')
                ->withAPrivateAttribute('$age', 'int')
                ->withAPublicAttribute('$phoneNumbers', 'string[]')
                ->withAMethod(Method::public('getAge', [], TypeDeclaration::from('int')))
                ->withAPublicMethod('changeThing', A::parameter('$name')->withType('string')->build())
                ->implementing(
                    (new InterfaceDefinition('FirstInterface'))->name(),
                    (new InterfaceDefinition('SecondInterface'))->name()
                )
                ->extending((new ClassDefinition('ParentClass'))->name())
                ->build(),
            $codebase->get('ClassName')
        );
    }

    /** @test */
    function it_builds_a_structure()
    {
        $builder = new CodebaseBuilder();
        $definitions = new RawDefinitions();
        $resolver = new ExternalDefinitionsResolver();

        $definitions->add(RawDefinition::class([
            'class' => 'AClass',
            'implements' => [
                'AnInterface',
            ],
            'extends' => 'ExternalClass',
        ]));
        $definitions->add(RawDefinition::class([
            'class' => 'AnotherClass',
            'extends' => 'AClass',
            'implements' => [
                'AnInterface',
                'ExternalInterface'
            ]
        ]));
        $definitions->add(RawDefinition::interface([
            'interface' => 'AnInterface',
            'extends' => ['ExternalInterface'],
        ]));
        $resolver->resolve($definitions);

        $codebase = $builder->buildFrom($definitions);

        $this->assertCount(5, $codebase->definitions());
        $this->assertTrue($codebase->has('AClass'));
        $this->assertInstanceOf(ClassDefinition::class, $codebase->get('AClass'));
        $this->assertTrue($codebase->has('AnotherClass'));
        $this->assertInstanceOf(ClassDefinition::class, $codebase->get('AnotherClass'));
        $this->assertTrue($codebase->has('ExternalClass'));
        $this->assertInstanceOf(ClassDefinition::class, $codebase->get('ExternalClass'));
        $this->assertTrue($codebase->has('AnInterface'));
        $this->assertInstanceOf(InterfaceDefinition::class, $codebase->get('AnInterface'));
        $this->assertTrue($codebase->has('ExternalInterface'));
        $this->assertInstanceOf(InterfaceDefinition::class, $codebase->get('ExternalInterface'));
        $this->assertEquals($codebase->get('AClass')->parent(), $codebase->get('ExternalClass')->name());
    }
}
