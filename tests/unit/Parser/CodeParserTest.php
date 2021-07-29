<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use PHPUnit\Framework\TestCase;
use PhUml\Fakes\StringCodeFinder;
use PhUml\TestBuilders\A;

final class CodeParserTest extends TestCase
{
    /** @test */
    function it_parses_a_class_with_no_attributes_and_no_methods()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
class MyClass
{
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $class = A::classNamed('MyClass');
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_parses_access_modifiers_for_attributes()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
class MyClass
{
    private $name;
    protected $age;
    public $phone;
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $class = A::class('MyClass')
            ->withAPrivateAttribute('$name')
            ->withAProtectedAttribute('$age')
            ->withAPublicAttribute('$phone')
            ->build();
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
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
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $class = A::class('MyClass')
            ->withAPrivateAttribute('$names', 'string[]')
            ->withAProtectedAttribute('$age', 'int')
            ->withAPublicAttribute('$phones', 'string[]')
            ->build();
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_parses_access_modifiers_for_methods()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
class MyClass
{
    /** @param string $newName */
    private function changeName($newName): void
    {
    }
    protected function getAge(): int 
    {
        return 0;
    }
    /** @param string $format */
    public function formatPhone($format): string
    {
    }
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $class = A::class('MyClass')
            ->withAMethod(
                A::method('changeName')
                    ->private()
                    ->withParameters(A::parameter('$newName')->withType('string')->build())
                    ->withReturnType('void')
                    ->build()
            )
            ->withAMethod(
                A::method('getAge')
                    ->protected()
                    ->withReturnType('int')
                    ->build()
            )->withAMethod(
                A::method('formatPhone')
                ->public()
                ->withParameters(A::parameter('$format')->withType('string')->build())
                ->withReturnType('string')
                ->build()
            )->build();
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_parses_methods_and_its_arguments()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
class MyClass
{
    public function __construct()
    {
    }
    /**
     * @param string $name
     * @param int $age
     * @param string $phone
     */
    public function changeValues($name, $age, $phone): void
    {
    }
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $class = A::class('MyClass')
            ->withAPublicMethod('__construct')
            ->withAMethod(
                A::method('changeValues')
                    ->public()
                    ->withParameters(
                        A::parameter('$name')->withType('string')->build(),
                        A::parameter('$age')->withType('int')->build(),
                        A::parameter('$phone')->withType('string')->build()
                    )
                    ->withReturnType('void')
                    ->build()
            )
            ->build();
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_parses_parent_child_class_relationships()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
class ParentClass
{
}
CLASS
        );
        $this->finder->add(
            <<<'CLASS'
<?php
class ChildClass extends ParentClass
{
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $parentClass = A::classNamed('ParentClass');
        $childClass = A::class('ChildClass')->extending($parentClass->name())->build();

        $this->assertTrue($codebase->has($parentClass->name()));
        $this->assertEquals($parentClass, $codebase->get($parentClass->name()));
        $this->assertTrue($codebase->has($childClass->name()));
        $this->assertEquals($childClass, $codebase->get($childClass->name()));
    }

    /** @test */
    function it_parses_a_class_implementing_interfaces()
    {
        $this->finder->add(
            <<<'CLASS'
<?php
interface InterfaceOne
{
}
CLASS
        );
        $this->finder->add(
            <<<'CLASS'
<?php
interface InterfaceTwo
{
}
CLASS
        );
        $this->finder->add(
            <<<'CLASS'
<?php
class MyClass implements InterfaceOne, InterfaceTwo
{
}
CLASS
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $interfaceOne = A::interfaceNamed('InterfaceOne');
        $interfaceTwo = A::interfaceNamed('InterfaceTwo');
        $class = A::class('MyClass')->implementing($interfaceOne->name(), $interfaceTwo->name())->build();

        $this->assertTrue($codebase->has($interfaceOne->name()));
        $this->assertEquals($interfaceOne, $codebase->get($interfaceOne->name()));
        $this->assertTrue($codebase->has($interfaceTwo->name()));
        $this->assertEquals($interfaceTwo, $codebase->get($interfaceTwo->name()));
        $this->assertTrue($codebase->has($class->name()));
        $this->assertEquals($class, $codebase->get($class->name()));
    }

    /** @test */
    function it_parses_an_interface_with_methods()
    {
        $this->finder->add(
            <<<'INTERFACE'
<?php
interface MyInterface
{
    /**
     * @param string $name
     * @param int $age
     * @param string $phone
     */
    public function changeValues($name, $age, $phone): void;
    public function ageToMonths(): int;
}
INTERFACE
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $interface = A::interface('MyInterface')
            ->withAMethod(
                A::method('changeValues')
                    ->public()
                    ->withParameters(
                        A::parameter('$name')->withType('string')->build(),
                        A::parameter('$age')->withType('int')->build(),
                        A::parameter('$phone')->withType('string')->build()
                    )->withReturnType('void')
                    ->build()
            )
            ->withAMethod(
                A::method('ageToMonths')
                    ->public()
                    ->withReturnType('int')
                    ->build()
            )->build();
        $this->assertTrue($codebase->has($interface->name()));
        $this->assertEquals($interface, $codebase->get($interface->name()));
    }

    /** @test */
    function it_parses_parent_child_interface_relationships()
    {
        $this->finder->add(
            <<<'INTERFACE'
<?php
interface ParentInterface
{
}
INTERFACE
        );
        $this->finder->add(
            <<<'INTERFACE'
<?php
interface ChildInterface extends ParentInterface
{
}
INTERFACE
        );
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $parentInterface = A::interfaceNamed('ParentInterface');
        $childInterface = A::interface('ChildInterface')->extending($parentInterface->name())->build();
        $this->assertTrue($codebase->has($parentInterface->name()));
        $this->assertEquals($parentInterface, $codebase->get($parentInterface->name()));
        $this->assertTrue($codebase->has($childInterface->name()));
        $this->assertEquals($childInterface, $codebase->get($childInterface->name()));
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
    /** @param string $name */
    public function named($name): array;
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
    
    /** @param string $name */
    public function __construct($name)
    {
        $this->name = $name;
    }
    /** @param string $name */
    public function isNamed($name): bool
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
    
    /** @param string $name */
    public function __construct($name)
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
    /** @param string $name */
    public function named($name): array
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
        $sourceCode = $this->finder->find($this->directory);

        $codebase = $this->parser->parse($sourceCode);

        $user = A::class('User')
            ->withAProtectedAttribute('$name', 'string')
            ->withAPublicMethod('__construct', A::parameter('$name')->withType('string')->build())
            ->withAMethod(
                A::method('isNamed')
                ->public()
                ->withParameters(A::parameter('$name')->withType('string')->build())
                ->withReturnType('bool')
                ->build()
            )->build();
        $currentMethod = A::method('current')
            ->public()
            ->withReturnType('Page')
            ->build();
        $pageable = A::interface('Pageable')->withAMethod($currentMethod)->build();
        $namedMethod = A::method('named')
            ->public()
            ->withParameters(A::parameter('$name')->withType('string')->build())
            ->withReturnType('array')
            ->build();
        $students = A::interface('Students')
            ->withAMethod($namedMethod)
            ->extending($pageable->name())
            ->build();
        $student = A::class('Student')
            ->withAPrivateAttribute('$grades', 'string[]')
            ->withAPublicMethod('__construct', A::parameter('$name')->withType('string')->build())
            ->extending($user->name())
            ->build();
        $inMemoryStudents = A::class('InMemoryStudents')
            ->withAPrivateAttribute('$students', "{$student->name()}[]")
            ->withAPrivateAttribute('$page')
            ->withAPublicMethod('__construct', A::parameter('$page')->withType('Page')->build())
            ->withAMethod($currentMethod)
            ->withAMethod($namedMethod)
            ->implementing($students->name())
            ->build();

        $this->assertTrue($codebase->has($user->name()));
        $this->assertEquals($user, $codebase->get($user->name()));
        $this->assertTrue($codebase->has($student->name()));
        $this->assertEquals($student, $codebase->get($student->name()));
        $this->assertTrue($codebase->has($inMemoryStudents->name()));
        $this->assertEquals($inMemoryStudents, $codebase->get($inMemoryStudents->name()));
        $this->assertTrue($codebase->has($pageable->name()));
        $this->assertEquals($pageable, $codebase->get($pageable->name()));
        $this->assertTrue($codebase->has($students->name()));
        $this->assertEquals($students, $codebase->get($students->name()));
    }

    /** @before */
    function let()
    {
        $this->parser = CodeParser::fromConfiguration(A::codeParserConfiguration()->build());
        $this->finder = new StringCodeFinder();
        $this->directory = new CodebaseDirectory(__DIR__);
    }

    private CodeParser $parser;

    private StringCodeFinder $finder;

    private CodebaseDirectory $directory;
}
