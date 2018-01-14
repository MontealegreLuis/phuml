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
use PhUml\Code\TypeDeclaration;
use PhUml\Code\Variable;
use PhUml\Fakes\StringCodeFinder;

class TokenParserTest extends TestCase
{
    /** @before */
    function buildParser()
    {
        $this->parser = new TokenParser();
        $this->finder = new StringCodeFinder();
    }

    /** @test */
    function it_parses_a_class_with_no_attributes_and_no_methods()
    {
        $this->finder->add(<<<'CLASS'
<?php
class MyClass
{
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $class = new ClassDefinition('MyClass');
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_access_modifiers_for_attributes()
    {
        $this->finder->add(<<<'CLASS'
<?php
class MyClass
{
    private $name;
    protected $age;
    public $phone;
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $class = new ClassDefinition('MyClass', [
            Attribute::private('$name'),
            Attribute::protected('$age'),
            Attribute::public('$phone'),
        ]);
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_type_declarations_for_attributes_from_annotations()
    {
        $class = <<<'CLASS'
<?php
class MyClass
{
    /**
     * @var array(string => string)
     */
    private $names;
    
    /** 
     * @var int 
     */
    protected $age;
    
    /**
     * @var array(string)
     */
    public $phones;
}
CLASS;
        $this->finder->add($class);

        $structure = $this->parser->parse($this->finder);

        $class = new ClassDefinition('MyClass', [
            Attribute::private('$names', TypeDeclaration::from('string')),
            Attribute::protected('$age', TypeDeclaration::from('int')),
            Attribute::public('$phones', TypeDeclaration::from('string')),
        ]);
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_access_modifiers_for_methods()
    {
        $this->finder->add(<<<'CLASS'
<?php
class MyClass
{
    private function changeName(string $newName): void
    {
    }
    protected function getAge(): int 
    {
        return 0;
    }
    public function formatPhone(string $format): string
    {
    }
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $class = new ClassDefinition('MyClass', [], [
            new Method('changeName', 'private', [new Variable('$newName', TypeDeclaration::from('string'))]),
            new Method('getAge', 'protected'),
            new Method('formatPhone', 'public', [new Variable('$format', TypeDeclaration::from('string'))]),
        ]);
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_methods_and_its_arguments()
    {
        $this->finder->add(<<<'CLASS'
<?php
class MyClass
{
    public function __construct()
    {
    }
    public function changeValues(string $name, int $age, string $phone): void
    {
    }
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $class = new ClassDefinition('MyClass', [], [
            new Method('__construct'),
            new Method('changeValues', 'public', [
                new Variable('$name', TypeDeclaration::from('string')),
                new Variable('$age', TypeDeclaration::from('int')),
                new Variable('$phone', TypeDeclaration::from('string')),
            ])
        ]);
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_parent_child_class_relationships()
    {
        $this->finder->add(<<<'CLASS'
<?php
class ParentClass
{
}
CLASS
        );
        $this->finder->add(<<<'CLASS'
<?php
class ChildClass extends ParentClass
{
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $parentClass = new ClassDefinition('ParentClass');
        $childClass = new ClassDefinition('ChildClass', [], [], [], $parentClass);

        $this->assertTrue($structure->has('ParentClass'));
        $this->assertEquals($parentClass, $structure->get('ParentClass'));
        $this->assertTrue($structure->has('ChildClass'));
        $this->assertEquals($childClass, $structure->get('ChildClass'));
    }

    /** @test */
    function it_parses_a_class_implementing_interfaces()
    {
        $this->finder->add(<<<'CLASS'
<?php
interface InterfaceOne
{
}
CLASS
        );
        $this->finder->add(<<<'CLASS'
<?php
interface InterfaceTwo
{
}
CLASS
        );
        $this->finder->add(<<<'CLASS'
<?php
class MyClass implements InterfaceOne, InterfaceTwo
{
}
CLASS
        );

        $structure = $this->parser->parse($this->finder);

        $interfaceOne = new InterfaceDefinition('InterfaceOne');
        $interfaceTwo = new InterfaceDefinition('InterfaceTwo');
        $class = new ClassDefinition('MyClass', [], [], [$interfaceOne, $interfaceTwo]);

        $this->assertTrue($structure->has('InterfaceOne'));
        $this->assertEquals($interfaceOne, $structure->get('InterfaceOne'));
        $this->assertTrue($structure->has('InterfaceTwo'));
        $this->assertEquals($interfaceTwo, $structure->get('InterfaceTwo'));
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_an_interface_with_methods()
    {
        $this->finder->add(<<<'INTERFACE'
<?php
interface MyInterface
{
    public function changeValues(string $name, int $age, string $phone): void;
    public function ageToMonths(): int;
}
INTERFACE
        );

        $structure = $this->parser->parse($this->finder);

        $interface = new InterfaceDefinition('MyInterface', [
            new Method('changeValues', 'public', [
                new Variable('$name', TypeDeclaration::from('string')),
                new Variable('$age', TypeDeclaration::from('int')),
                new Variable('$phone', TypeDeclaration::from('string')),
            ]),
            new Method('ageToMonths', 'public')
        ]);
        $this->assertTrue($structure->has('MyInterface'));
        $this->assertEquals($interface, $structure->get('MyInterface'));
    }

    /** @test */
    function it_parses_parent_child_interface_relationships()
    {
        $this->finder->add(<<<'INTERFACE'
<?php
interface ParentInterface
{
}
INTERFACE
        );
        $this->finder->add(<<<'INTERFACE'
<?php
interface ChildInterface extends ParentInterface
{
}
INTERFACE
        );

        $structure = $this->parser->parse($this->finder);

        $parentInterface = new InterfaceDefinition('ParentInterface');
        $childInterface = new InterfaceDefinition('ChildInterface', [], $parentInterface);
        $this->assertTrue($structure->has('ParentInterface'));
        $this->assertEquals($parentInterface, $structure->get('ParentInterface'));
        $this->assertTrue($structure->has('ChildInterface'));
        $this->assertEquals($childInterface, $structure->get('ChildInterface'));
    }

    /** @test */
    function it_parses_both_classes_and_interfaces()
    {
        $parentInterfaceCode = <<<'INTERFACE'
<?php
interface Pageable
{
    public function current(): Page;
}
INTERFACE;
        $childInterfaceCode = <<<'INTERFACE'
<?php
interface Students extends Pageable
{
    public function named(string $name): array;
}
INTERFACE;
        $parentClassCode = <<<'CLASS'
<?php
class User
{
    /**
     * @var string
     */
    protected $name;
    public function __construct(string $name)
    {
        $this->name = $name;
    }
    public function isNamed(string $name): bool
    {
        return $this->name === $name;
    }
}
CLASS;
        $childClassCode = <<<'CLASS'
<?php
class Student extends User
{
    /**
     * @var array(int => string)
     */
    private $grades;
    
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->grades = []; // no grades at the beginning...
    }
}
CLASS;
        $classCode = <<<'CLASS'
<?php
class InMemoryStudents implements Students
{
    /**
     * @var array(int => Student)
     */
    private $students;
    
    private $page;
    
    public function __construct(Page $page)
    {
        $this->page = $page;
        $this->students = [];
    }
    public function current(): Page
    {
        return $this->page->withElements(
            array_slice($this->students, $this->page->offset(), $this->page->size())
        ); 
    }
    public function named(string $name): array
    {
        $matching = [];
        foreach($this->students as $student) {
            if ($student->isNamed($name)) {
                $matching[] = $student;
            }
        }
        return $matching;
    }
}
CLASS;

        $this->finder->add($parentInterfaceCode);
        $this->finder->add($childInterfaceCode);
        $this->finder->add($parentClassCode);
        $this->finder->add($childClassCode);
        $this->finder->add($classCode);

        $structure = $this->parser->parse($this->finder);

        $user = new ClassDefinition('User', [
            Attribute::protected('$name', TypeDeclaration::from('string'))
        ], [
            new Method('__construct', 'public', [new Variable('$name', TypeDeclaration::from('string'))]),
            new Method('isNamed', 'public', [new Variable('$name', TypeDeclaration::from('string'))])
        ]);
        $pageable = new InterfaceDefinition('Pageable', [
            new Method('current'),
        ]);
        $students = new InterfaceDefinition('Students', [
            new Method('named', 'public', [new Variable('$name', TypeDeclaration::from('string'))]),
        ], $pageable);
        $student = new ClassDefinition('Student', [
            Attribute::private('$grades', TypeDeclaration::from('string'))
        ], [
            new Method('__construct', 'public', [new Variable('$name', TypeDeclaration::from('string'))]),
        ], [], $user);
        $inMemoryStudents = new ClassDefinition('InMemoryStudents', [
            Attribute::private('$students', TypeDeclaration::from('Student')),
            Attribute::private('$page'),
        ], [
            new Method('__construct', 'public', [new Variable('$page', TypeDeclaration::from('Page'))]),
            new Method('current'),
            new Method('named', 'public', [new Variable('$name', TypeDeclaration::from('string'))]),
        ], [
            $students,
        ]);

        $this->assertTrue($structure->has('User'));
        $this->assertEquals($user, $structure->get('User'));
        $this->assertTrue($structure->has('Student'));
        $this->assertEquals($student, $structure->get('Student'));
        $this->assertTrue($structure->has('InMemoryStudents'));
        $this->assertEquals($inMemoryStudents, $structure->get('InMemoryStudents'));
        $this->assertTrue($structure->has('Pageable'));
        $this->assertEquals($pageable, $structure->get('Pageable'));
        $this->assertTrue($structure->has('Students'));
        $this->assertEquals($students, $structure->get('Students'));
    }

    /** @var TokenParser */
    private $parser;

    /** @var StringCodeFinder */
    private $finder;
}
