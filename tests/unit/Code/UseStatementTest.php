<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class UseStatementTest extends TestCase
{
    /** @test */
    function it_knows_if_a_short_name_matches_a_fully_qualified_name()
    {
        $useStatement = new UseStatement(new Name(Name::class), alias: null);

        $this->assertTrue($useStatement->endsWith(new Name('Name')));
        $this->assertFalse($useStatement->endsWith(new Name('Package')));
    }

    /** @test */
    function it_knows_if_a_short_name_matches_an_alias()
    {
        $useStatement = new UseStatement(new Name(Name::class), new Name('MyAlias'));

        $this->assertTrue($useStatement->isAliasedAs(new Name('MyAlias')));
    }

    /** @test */
    function it_knows_if_a_short_name_with_array_suffix_matches_a_fully_qualified_name()
    {
        $useStatement = new UseStatement(new Name(Name::class), alias: null);

        $this->assertTrue($useStatement->endsWith(new Name('Name[]')));
    }

    /** @test */
    function it_knows_if_a_short_name_with_array_suffix_matches_an_alias()
    {
        $useStatement = new UseStatement(new Name(Name::class), new Name('MyAlias'));

        $this->assertTrue($useStatement->isAliasedAs(new Name('MyAlias[]')));
    }

    /** @test */
    function it_knows_its_fully_qualified_name()
    {
        $nameWithNamespace = new Name(Name::class);
        $nameWithoutNamespace = new Name(SplFileInfo::class);
        $nameForArray = new Name('Name[]');
        $useStatementWithNamespace = new UseStatement($nameWithNamespace, alias: null);
        $useStatementWithoutNamespace = new UseStatement($nameWithoutNamespace, alias: null);

        $this->assertEquals(
            'PhUml\Code\Name',
            $useStatementWithNamespace->fullyQualifiedName($nameWithNamespace)
        );
        $this->assertEquals(
            'PhUml\Code\Name[]',
            $useStatementWithNamespace->fullyQualifiedName($nameForArray)
        );
        $this->assertEquals(
            'SplFileInfo',
            $useStatementWithoutNamespace->fullyQualifiedName($nameWithoutNamespace)
        );
    }
}
