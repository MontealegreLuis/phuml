<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Name;
use PhUml\Code\Variables\TypeDeclaration;

final class EdgeKeyTest extends TestCase
{
    /** @test */
    function it_can_be_converted_to_string()
    {
        $keyA = EdgeKey::from(new Name('AClass'), TypeDeclaration::from('AnotherClass'));
        $keyB = EdgeKey::from(new Name('ThirdClass'), TypeDeclaration::from('FourthClass'));

        $this->assertSame('AClassAnotherClass', (string) $keyA);
        $this->assertSame('ThirdClassFourthClass', (string) $keyB);
        $this->assertSame((string) $keyA, (string) $keyA);
        $this->assertSame(
            (string) $keyB,
            (string) EdgeKey::from(
                new Name('ThirdClass'),
                TypeDeclaration::from('FourthClass')
            )
        );
        $this->assertNotSame((string) $keyA, (string) $keyB);
        $this->assertNotSame((string) $keyB, (string) $keyA);
    }
}
