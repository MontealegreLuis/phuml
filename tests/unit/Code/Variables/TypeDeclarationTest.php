<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use PHPUnit\Framework\TestCase;

final class TypeDeclarationTest extends TestCase
{
    /**
     * @test
     * @dataProvider builtInTypes
     */
    function it_knows_it_is_a_built_in_type(string $type)
    {
        $builtInType = TypeDeclaration::from($type);

        $isBuiltIn = $builtInType->isBuiltIn();

        $this->assertTrue($isBuiltIn);
    }

    /**
     * @test
     * @dataProvider pseudoTypes
     */
    function it_knows_it_is_a_pseudo_type(string $type)
    {
        $builtInType = TypeDeclaration::from($type);

        $isBuiltIn = $builtInType->isBuiltIn();

        $this->assertTrue($isBuiltIn);
    }

    /**
     * @test
     * @dataProvider aliasedTypes
     */
    function it_knows_it_is_an_alias_for_a_primitive_type(string $type)
    {
        $builtInType = TypeDeclaration::from($type);

        $isBuiltIn = $builtInType->isBuiltIn();

        $this->assertTrue($isBuiltIn);
    }

    /** @test */
    function it_knows_it_is_not_a_built_in_type()
    {
        $type = TypeDeclaration::from('MyClass');
        $arrayOfObjects = TypeDeclaration::from('MyClass[]');
        $unionType = TypeDeclaration::fromCompositeType(['MyClass', 'AnotherClass', 'null'], CompositeType::UNION);

        $isBuiltIn = $type->isBuiltIn();
        $isArrayOfObjects = $arrayOfObjects->isBuiltIn();
        $isUnionTypeBuiltIn = $unionType->isBuiltIn();

        $this->assertFalse($isBuiltIn);
        $this->assertFalse($isArrayOfObjects);
        $this->assertFalse($isUnionTypeBuiltIn);
    }

    /** @test */
    function it_knows_if_no_type_declaration_was_provided()
    {
        $noType = TypeDeclaration::absent();
        $nullType = TypeDeclaration::from(null);

        $this->assertFalse($noType->isPresent());
        $this->assertFalse($noType->isBuiltIn());
        $this->assertFalse($nullType->isPresent());
        $this->assertFalse($nullType->isBuiltIn());
    }

    /** @test */
    function it_knows_it_is_a_nullable_type()
    {
        $unionType = TypeDeclaration::fromCompositeType(['string', 'int', 'null'], CompositeType::UNION);
        $regularType = TypeDeclaration::from('string');
        $nullableType = TypeDeclaration::fromNullable('string');

        $this->assertFalse($unionType->isNullable());
        $this->assertFalse($regularType->isNullable());
        $this->assertTrue($nullableType->isNullable());
        $this->assertSame('?string', (string) $nullableType);
    }

    /** @test */
    function it_represents_to_string_union_types()
    {
        $unionTypeA = TypeDeclaration::fromCompositeType(['string', 'int', 'null'], CompositeType::UNION);
        $unionTypeB = TypeDeclaration::fromCompositeType(['MyClass', 'AnotherClass'], CompositeType::UNION);

        $this->assertSame('string|int|null', (string) $unionTypeA);
        $this->assertSame('MyClass|AnotherClass', (string) $unionTypeB);
    }

    /** @test */
    function it_represents_to_string_intersection_types()
    {
        $intersectionTypeA = TypeDeclaration::fromCompositeType(['string', 'int', 'null'], CompositeType::INTERSECTION);
        $intersectionTypeB = TypeDeclaration::fromCompositeType(['MyClass', 'AnotherClass'], CompositeType::INTERSECTION);

        $this->assertSame('string&int&null', (string) $intersectionTypeA);
        $this->assertSame('MyClass&AnotherClass', (string) $intersectionTypeB);
    }

    /** @test */
    function it_extracts_reference_types_from_union_types()
    {
        $unionType = TypeDeclaration::fromCompositeType(
            [
                'string',
                'AClass',
                'null',
                'AnotherClass[]',
                'Class\\With\\Namespace',
            ],
            CompositeType::UNION
        );

        $references = $unionType->references();

        $this->assertCount(3, $references);
        $this->assertSame('AClass', (string) $references[1]);
        $this->assertSame('AnotherClass', (string) $references[3]);
        $this->assertSame('Class\\With\\Namespace', $references[4]->fullName());
    }

    /** @test */
    function it_extracts_reference_types_from_intersection_types()
    {
        $intersectionType = TypeDeclaration::fromCompositeType(
            [
                'string',
                'AClass',
                'null',
                'AnotherClass[]',
                'Class\\With\\Namespace',
            ],
            CompositeType::INTERSECTION
        );

        $references = $intersectionType->references();

        $this->assertCount(3, $references);
        $this->assertSame('AClass', (string) $references[1]);
        $this->assertSame('AnotherClass', (string) $references[3]);
        $this->assertSame('Class\\With\\Namespace', $references[4]->fullName());
    }

    /** @test */
    function it_knows_if_it_is_built_in_array_type()
    {
        $string = TypeDeclaration::from('string');
        $arrayOfString = TypeDeclaration::from('string[]');
        $arrayOfObjects = TypeDeclaration::from('MyClass[]');
        $builtInArray = TypeDeclaration::from('array');

        $this->assertFalse($string->isBuiltInArray());
        $this->assertFalse($arrayOfString->isBuiltInArray());
        $this->assertFalse($arrayOfObjects->isBuiltInArray());
        $this->assertTrue($builtInArray->isBuiltInArray());
    }

    function builtInTypes()
    {
        return [
            'int' => ['int'],
            'int[]' => ['int[]'], // It also recognizes arrays of built-in types
            'float' => ['float'],
            'bool' => ['bool'],
            'string' => ['string'],
            'array' => ['array'],
            'callable' => ['callable'],
            'iterable' => ['iterable'],
            'mixed' => ['mixed'],
            'object' => ['object'],
            'never' => ['never'],
        ];
    }

    function pseudoTypes()
    {
        return [
            'resource' => ['resource'],
        ];
    }

    public function aliasedTypes()
    {
        return [
            'boolean' => ['boolean'],
            'integer' => ['integer'],
            'number' => ['number'],
            'double' => ['double'],
        ];
    }
}
