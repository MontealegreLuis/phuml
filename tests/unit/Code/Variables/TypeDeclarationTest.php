<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
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

        $isBuiltIn = $type->isBuiltIn();

        $this->assertFalse($isBuiltIn);
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
        $nullableType = TypeDeclaration::fromNullable('string');

        $this->assertTrue($nullableType->isNullable());
        $this->assertEquals('?string', (string) $nullableType);
    }

    function builtInTypes()
    {
        return [
            'int' => ['int'],
            'float' => ['float'],
            'bool' => ['bool'],
            'string' => ['string'],
            'array' => ['array'],
            'callable' => ['callable'],
            'iterable' => ['iterable'],
        ];
    }

    function pseudoTypes()
    {
        return [
            'mixed' => ['mixed'],
            'number' => ['number'],
            'object' => ['object'],
            'resource' => ['resource'],
            'self' => ['self'],
        ];
    }

    public function aliasedTypes()
    {
        return [
            'boolean' => ['boolean'],
            'integer' => ['integer'],
            'double' => ['double'],
        ];
    }
}
