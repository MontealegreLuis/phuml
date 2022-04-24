<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\ContractTests;

use PhUml\Code\Properties\HasProperties;
use PhUml\Code\Properties\Property;
use PhUml\TestBuilders\A;

trait WithPropertiesTests
{
    /** @test */
    function it_has_by_default_no_properties()
    {
        $noPropertiesClass = $this->definitionWithProperties();

        $properties = $noPropertiesClass->properties();

        $this->assertEmpty($properties);
    }

    /** @test */
    function it_knows_its_properties()
    {
        $properties = [
            A::property('$firstAttribute')->public()->build(),
            A::property('$secondAttribute')->public()->build(),
        ];
        $classWithProperties = $this->definitionWithProperties($properties);

        $classProperties = $classWithProperties->properties();

        $this->assertEquals($properties, $classProperties);
    }

    /** @param Property[] $properties */
    abstract protected function definitionWithProperties(array $properties = []): HasProperties;
}
