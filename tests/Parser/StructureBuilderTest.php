<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Parser\Raw\ExternalDefinitionsResolver;
use PhUml\Parser\Raw\RawDefinition;
use PhUml\Parser\Raw\RawDefinitions;
use PhUml\TestBuilders\A;

class StructureBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_interface_definition()
    {
        $builder = new StructureBuilder();
        $definitions = new RawDefinitions();
        $definitions->add(RawDefinition::interface(['interface' => 'ParentInterface']));
        $definitions->add(RawDefinition::interface([
            'interface' => 'InterfaceName',
            'methods' => [
                ['doSomething', 'public', [], false, false],
                ['changeThing', 'public', [['$name', 'string']], false, false],
            ],
            'extends' => 'ParentInterface',
        ]));

        $structure = $builder->buildFrom($definitions);
        $interface = $structure->get('InterfaceName');

        $this->assertEquals(A::interface('InterfaceName')
            ->withAPublicMethod('doSomething')
            ->withAPublicMethod('changeThing', A::parameter('$name')->withType('string')->build())
            ->extending(new InterfaceDefinition('ParentInterface'))
            ->build(),
            $interface
        );
    }

    /** @test */
    function it_builds_a_class_definition()
    {
        $builder = new StructureBuilder();
        $definitions = new RawDefinitions();
        $definitions->add(RawDefinition::interface(['interface' => 'FirstInterface']));
        $definitions->add(RawDefinition::interface(['interface' => 'SecondInterface']));
        $definitions->add(RawDefinition::class(['class' => 'ParentClass']));
        $definitions->add(RawDefinition::class([
            'class' => 'ClassName',
            'attributes' => [
                ['$name', 'protected', null, false],
                ['$age', 'private', '/**
                                      * @var int 
                                      */', false],
                ['$phoneNumbers', 'public', '/**
                                              * @var array(int => string) 
                                              */', false],
            ],
            'methods' => [
                ['doSomething', 'public', [], false, false],
                ['changeThing', 'public', [['$name', 'string']], false, false],
            ],
            'implements' => [
                'FirstInterface',
                'SecondInterface',
            ],
            'extends' => 'ParentClass',
        ]));

        $structure = $builder->buildFrom($definitions);
        $class = $structure->get('ClassName');

        $this->assertEquals(
            A::class('ClassName')
                ->withAProtectedAttribute('$name')
                ->withAPrivateAttribute('$age', 'int')
                ->withAPublicAttribute('$phoneNumbers', 'string')
                ->withAPublicMethod('doSomething')
                ->withAPublicMethod('changeThing', A::parameter('$name')->withType('string')->build())
                ->implementing(new InterfaceDefinition('FirstInterface'), new InterfaceDefinition('SecondInterface'))
                ->extending(new ClassDefinition('ParentClass'))
                ->build(),
            $class
        );
    }

    /** @test */
    function it_builds_a_structure()
    {
        $builder = new StructureBuilder();
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
            'extends' => 'ExternalInterface',
        ]));
        $resolver->resolve($definitions);

        $structure = $builder->buildFrom($definitions);

        $this->assertCount(5, $structure->definitions());
        $this->assertTrue($structure->has('AClass'));
        $this->assertInstanceOf(ClassDefinition::class, $structure->get('AClass'));
        $this->assertTrue($structure->has('AnotherClass'));
        $this->assertInstanceOf(ClassDefinition::class, $structure->get('AnotherClass'));
        $this->assertTrue($structure->has('ExternalClass'));
        $this->assertInstanceOf(ClassDefinition::class, $structure->get('ExternalClass'));
        $this->assertTrue($structure->has('AnInterface'));
        $this->assertInstanceOf(InterfaceDefinition::class, $structure->get('AnInterface'));
        $this->assertTrue($structure->has('ExternalInterface'));
        $this->assertInstanceOf(InterfaceDefinition::class, $structure->get('ExternalInterface'));
        $this->assertEquals(
            $structure->get('AClass')->extends()->identifier(),
            $structure->get('ExternalClass')->identifier()
        );
    }
}
