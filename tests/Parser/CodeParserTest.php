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
use PhUml\Fakes\StringCodeFinder;
use PhUml\TestBuilders\A;

class CodeParserTest extends TestCase
{
    /** @before */
    function buildParser()
    {
        $this->parser = new CodeParser();
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

        $class = A::class('MyClass')
            ->withAPrivateAttribute('$name')
            ->withAProtectedAttribute('$age')
            ->withAPublicAttribute('$phone')
            ->build();
        $this->assertTrue($structure->has('MyClass'));
        $this->assertEquals($class, $structure->get('MyClass'));
    }

    /** @test */
    function it_parses_type_declarations_for_attributes_from_doc_blocks()
    {
        $class = <<<'CLASS'
<?php
class MyClass
{
    /**
     * @var string[]
     */
    private $names;
    
    /** 
     * @var int 
     */
    protected $age;
    
    /**
     * @var string[]
     */
    public $phones;
}
CLASS;
        $this->finder->add($class);

        $structure = $this->parser->parse($this->finder);

        $class = A::class('MyClass')
            ->withAPrivateAttribute('$names', 'string[]')
            ->withAProtectedAttribute('$age', 'int')
            ->withAPublicAttribute('$phones', 'string[]')
            ->build();
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

        $class = A::class('MyClass')
            ->withAPrivateMethod('changeName', A::parameter('$newName')->withType('string')->build())
            ->withAProtectedMethod('getAge')
            ->withAPublicMethod('formatPhone', A::parameter('$format')->withType('string')->build())
            ->build();
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

        $class = A::class('MyClass')
            ->withAPublicMethod('__construct')
            ->withAPublicMethod(
                'changeValues',
                A::parameter('$name')->withType('string')->build(),
                A::parameter('$age')->withType('int')->build(),
                A::parameter('$phone')->withType('string')->build()
            )
            ->build();
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
        $childClass = A::class('ChildClass')->extending($parentClass)->build();

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
        $class = A::class('MyClass')->implementing($interfaceOne, $interfaceTwo)->build();

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

        $interface = A::interface('MyInterface')
            ->withAPublicMethod(
                'changeValues',
                A::parameter('$name')->withType('string')->build(),
                A::parameter('$age')->withType('int')->build(),
                A::parameter('$phone')->withType('string')->build()
            )
            ->withAPublicMethod('ageToMonths')
            ->build();
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
        $childInterface = A::interface('ChildInterface')->extending($parentInterface)->build();
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
     * @var string[]
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
     * @var Student[]
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

        $user = A::class('User')
            ->withAProtectedAttribute('$name', 'string')
            ->withAPublicMethod('__construct', A::parameter('$name')->withType('string')->build())
            ->withAPublicMethod('isNamed', A::parameter('$name')->withType('string')->build())
            ->build();
        $pageable = A::interface('Pageable')->withAPublicMethod('current')->build();
        $students = A::interface('Students')
            ->withAPublicMethod('named', A::parameter('$name')->withType('string')->build())
            ->extending($pageable)
            ->build();
        $student = A::class('Student')
            ->withAPrivateAttribute('$grades', 'string[]')
            ->withAPublicMethod('__construct', A::parameter('$name')->withType('string')->build())
            ->extending($user)
            ->build();
        $inMemoryStudents = A::class('InMemoryStudents')
            ->withAPrivateAttribute('$students', "{$student->name()}[]")
            ->withAPrivateAttribute('$page')
            ->withAPublicMethod('__construct', A::parameter('$page')->withType('Page')->build())
            ->withAPublicMethod('current')
            ->withAPublicMethod('named', A::parameter('$name')->withType('string')->build())
            ->implementing($students)
            ->build();

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

    /** @var CodeParser */
    private $parser;

    /** @var StringCodeFinder */
    private $finder;
}
