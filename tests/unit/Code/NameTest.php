<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class NameTest extends TestCase
{
    /** @test */
    function it_cannot_be_empty()
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('     ');
    }

    /** @test */
    function it_trims_a_name()
    {
        $name = new Name('    TypeDeclaration   ');

        $this->assertEquals('TypeDeclaration', (string) $name);
    }

    /** @test */
    function it_can_hold_a_fully_qualified_name()
    {
        $fqn = Name::class;
        $name = new Name($fqn);

        $this->assertEquals($fqn, $name->fullName());
        $this->assertEquals('Name', (string) $name);
    }

    /** @test */
    function it_can_hold_built_in_type_names()
    {
        $string = 'string';
        $nameString = new Name($string);
        $splObject = SplFileInfo::class;
        $splObjectName = new Name($splObject);

        $this->assertEquals($string, $nameString->fullName());
        $this->assertEquals($string, (string) $nameString);
        $this->assertEquals($splObject, $splObjectName->fullName());
        $this->assertEquals($splObject, (string) $splObjectName);
    }
}
