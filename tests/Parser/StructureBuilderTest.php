<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Attribute;
use PhUml\Code\ClassDefinition;
use PhUml\Code\InterfaceDefinition;
use PhUml\Code\Method;
use PhUml\Code\Variable;

class StructureBuilderTest extends TestCase
{
    /** @test */
    function it_builds_an_interface_definition()
    {
        $builder = new StructureBuilder();
        $definitions = new Definitions();
        $definitions->add(['interface' => 'ParentInterface']);
        $definitions->add([
            'interface' => 'InterfaceName',
            'functions' => [
                ['doSomething', 'public', []],
                ['changeThing', 'public', [['string', '$name']]],
            ],
            'extends' => 'ParentInterface',
        ]);

        $structure = $builder->buildFromDefinitions($definitions);
        $interface = $structure->get('InterfaceName');

        $this->assertEquals(
            new InterfaceDefinition('InterfaceName', [
                new Method('doSomething'),
                new Method('changeThing', 'public', [
                    new Variable('$name', 'string'),
                ]),
            ], new InterfaceDefinition('ParentInterface')),
            $interface
        );
    }

    /** @test */
    function it_builds_a_class_definition()
    {
        $builder = new StructureBuilder();
        $definitions = new Definitions();
        $definitions->add(['interface' => 'FirstInterface']);
        $definitions->add(['interface' => 'SecondInterface']);
        $definitions->add(['class' => 'ParentClass']);
        $definitions->add([
            'class' => 'ClassName',
            'attributes' => [
                ['$name', 'protected', null],
                ['$age', 'private', '/**
                                      * @var int 
                                      */'],
                ['$phoneNumbers', 'public', '/**
                                              * @var array(int => string) 
                                              */'],
            ],
            'functions' => [
                ['doSomething', 'public', []],
                ['changeThing', 'public', [['string', '$name']]],
            ],
            'implements' => [
                'FirstInterface',
                'SecondInterface',
            ],
            'extends' => 'ParentClass',
        ]);

        $structure = $builder->buildFromDefinitions($definitions);
        $class = $structure->get('ClassName');

        $this->assertEquals(
            new ClassDefinition('ClassName', [
                new Attribute('$name', 'protected'),
                new Attribute('$age', 'private', 'int'),
                new Attribute('$phoneNumbers', 'public', 'string'),
            ], [
                new Method('doSomething'),
                new Method('changeThing', 'public', [
                    new Variable('$name', 'string'),
                ]),
            ], [
                new InterfaceDefinition('FirstInterface'),
                new InterfaceDefinition('SecondInterface'),
            ],new ClassDefinition('ParentClass')),
            $class
        );
    }

    /** @test */
    function it_builds_a_structure()
    {
        $builder = new StructureBuilder();
        $definitions = new Definitions();
        $resolver = new RelationsResolver();

        $definitions->add([
            'class' => 'AClass',
            'implements' => [
                'AnInterface',
            ],
            'extends' => 'ExternalClass',
        ]);
        $definitions->add([
            'class' => 'AnotherClass',
            'extends' => 'AClass',
            'implements' => [
                'AnInterface',
                'ExternalInterface'
            ]
        ]);
        $definitions->add([
            'interface' => 'AnInterface',
            'extends' => 'ExternalInterface',
        ]);
        $resolver->resolve($definitions);

        $structure = $builder->buildFromDefinitions($definitions);

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
            $structure->get('AClass')->extends->identifier(),
            $structure->get('ExternalClass')->identifier()
        );
    }
}
