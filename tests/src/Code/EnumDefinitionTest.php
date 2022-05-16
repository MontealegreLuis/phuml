<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PHPUnit\Framework\TestCase;
use PhUml\TestBuilders\A;

final class EnumDefinitionTest extends TestCase
{
    /** @test */
    function it_determines_if_has_properties_by_counting_contants_and_cases()
    {
        $enumWithConstants = A::enum('EnumWithConstants')
            ->withConstants(A::constant('CONSTANT')->build())
            ->build();
        $enumWithCases = A::enum('EnumWithCases')
            ->withCases('PRIVATE', 'PROTECTED')
            ->build();
        $enumWithCasesAndConstants = A::enum('EnumWithConstants')
            ->withConstants(A::constant('CONSTANT')->build())
            ->withCases('PRIVATE', 'PROTECTED')
            ->build();
        $enumWithoutProperties = A::enum('EnumWithoutProperties')->build();

        $this->assertTrue($enumWithConstants->hasProperties());
        $this->assertTrue($enumWithCases->hasProperties());
        $this->assertTrue($enumWithCasesAndConstants->hasProperties());
        $this->assertFalse($enumWithoutProperties->hasProperties());
    }
}
