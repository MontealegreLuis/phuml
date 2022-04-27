<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

final class AttributeAnalyzerTest extends TestCase
{
    /** @test */
    function it_detects_attribute_classes()
    {
        $attributeClass = new Class_(new Identifier('AnAttributeClass'), [
            'attrGroups' => [
                new AttributeGroup([
                    new Attribute(new Name('Attribute')),
                ]),
            ],
        ]);
        $annotatedClass = new Class_(new Identifier('AnAnnotatedClass'), [
            'attrGroups' => [
                new AttributeGroup(
                    [
                        new Attribute(new Name('Command')),
                    ]
                ),
            ],
        ]);
        $regularClass = new Class_(new Identifier('ARegularClass'));
        $analyzer = new AttributeAnalyzer();

        $isAttribute = $analyzer->isAttribute($attributeClass);
        $isNotAttribute = $analyzer->isAttribute($regularClass);
        $isAnnotatedBuNotAttribute = $analyzer->isAttribute($annotatedClass);

        $this->assertTrue($isAttribute, 'It should have detected this is an attribute class');
        $this->assertFalse($isNotAttribute, 'It should have detected this is not an attribute class');
        $this->assertFalse($isAnnotatedBuNotAttribute, 'It should have detected this is not an attribute class');
    }
}
