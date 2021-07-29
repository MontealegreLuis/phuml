<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\Attributes\Attribute;
use PhUml\Code\Attributes\HasAttributes;
use PhUml\TestBuilders\A;

trait WithAttributesTests
{
    /** @test */
    function it_has_by_default_no_attributes()
    {
        $noAttributesClass = $this->definitionWithAttributes();

        $attributes = $noAttributesClass->attributes();

        $this->assertEmpty($attributes);
    }

    /** @test */
    function it_knows_its_attributes()
    {
        $attributes = [
            A::attribute('$firstAttribute')->public()->build(),
            A::attribute('$secondAttribute')->public()->build(),
        ];

        $classWithAttributes = $this->definitionWithAttributes($attributes);

        $classAttributes = $classWithAttributes->attributes();

        $this->assertEquals($attributes, $classAttributes);
    }

    /** @param Attribute[] $attributes */
    abstract protected function definitionWithAttributes(array $attributes = []): HasAttributes;
}
