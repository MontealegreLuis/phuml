<?php declare(strict_types=1);
/**
 * PHP version 8.1
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

        $this->assertSame('TypeDeclaration', (string) $name);
    }

    /** @test */
    function it_can_hold_a_fully_qualified_name()
    {
        $fqn = Name::class;
        $name = new Name($fqn);

        $this->assertSame($fqn, $name->fullName());
        $this->assertSame('Name', (string) $name);
    }

    /** @test */
    function it_can_hold_built_in_type_names()
    {
        $string = 'string';
        $nameString = new Name($string);
        $splObject = SplFileInfo::class;
        $splObjectName = new Name($splObject);

        $this->assertSame($string, $nameString->fullName());
        $this->assertSame($string, (string) $nameString);
        $this->assertSame($splObject, $splObjectName->fullName());
        $this->assertSame($splObject, (string) $splObjectName);
    }
}
