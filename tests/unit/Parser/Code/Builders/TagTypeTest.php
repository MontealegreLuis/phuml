<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Name;
use PhUml\Code\UseStatement;
use PhUml\Code\UseStatements;
use PhUml\Code\Variables\TypeDeclaration;

final class TagTypeTest extends TestCase
{
    /** @test */
    function it_resolves_fully_qualified_names_from_relative_names()
    {
        $useStatements = new UseStatements([new UseStatement(new Name('PhpParser\Node'), null)]);
        $tagType = TagType::named('Node\Param[]');

        $type = $tagType->resolve($useStatements);

        $this->assertEquals(TypeDeclaration::from('PhpParser\Node\Param[]'), $type);
    }

    /** @test */
    function it_resolves_fully_qualified_names_from_relative_names_with_multiple_prefix()
    {
        $useStatements = new UseStatements([new UseStatement(new Name('PhpParser\Node'), null)]);
        $tagType = TagType::named('Node\Another\Param[]');

        $type = $tagType->resolve($useStatements);

        $this->assertEquals(TypeDeclaration::from('PhpParser\Node\Another\Param[]'), $type);
    }
}
