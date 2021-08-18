<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;

final class UseStatementsTest extends TestCase
{
    /** @test */
    function it_gets_fully_qualified_name_from_alias()
    {
        $alias = new Name('MyClass');
        $useStatements = new UseStatements([
            new UseStatement(new Name('Package\\SubPackage\\OneClass'), alias: null),
            new UseStatement(new Name('Package\\SubPackage\\AnotherClass'), $alias),
        ]);

        $fullyQualifiedName = $useStatements->fullyQualifiedNameFor($alias);

        $this->assertEquals('Package\\SubPackage\\AnotherClass', $fullyQualifiedName);
    }
}
