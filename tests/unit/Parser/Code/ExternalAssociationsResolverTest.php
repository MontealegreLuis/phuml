<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\TestBuilders\A;

final class ExternalAssociationsResolverTest extends ExternalDefinitionsResolverTest
{
    /** @test */
    function it_adds_external_attributes()
    {
        $class = A::class('TestClass')
            ->withAPrivateAttribute('$referenceA', 'ReferenceA')
            ->withAPrivateAttribute('$referenceB', 'ReferenceB')
            ->withAPrivateAttribute('$referenceC', 'ReferenceC')
            ->withAPrivateAttribute('$notAReference', 'int')
            ->build()
        ;
        $codebase = new Codebase();
        $codebase->add($class);
        $resolver = new ExternalAssociationsResolver();

        $resolver->resolve($codebase);

        $this->assertTrue($codebase->has(new Name('ReferenceA')));
        $this->assertTrue($codebase->has(new Name('ReferenceB')));
        $this->assertTrue($codebase->has(new Name('ReferenceC')));
    }

    /** @test */
    function it_adds_external_associations_from_attributes_with_union_types()
    {
        $class = A::class('TestClass')
            ->withAPrivateAttribute('$unionReferences', 'ReferenceA|ReferenceB|ReferenceC|string|null')
            ->withAPrivateAttribute('$referenceD', 'ReferenceD')
            ->withAPrivateAttribute('$noType')
            ->withAPrivateAttribute('$notAReference', 'int')
            ->build();
        $codebase = new Codebase();
        $codebase->add($class);
        $resolver = new ExternalAssociationsResolver();

        $resolver->resolve($codebase);

        $this->assertTrue($codebase->has(new Name('ReferenceA')));
        $this->assertTrue($codebase->has(new Name('ReferenceB')));
        $this->assertTrue($codebase->has(new Name('ReferenceC')));
    }

    /** @test */
    function it_adds_external_constructor_parameters()
    {
        $class = A::class('TestClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$referenceA')->withType('ReferenceA')->build(),
                A::parameter('$referenceB')->withType('ReferenceB')->build(),
                A::parameter('$referenceC')->withType('ReferenceC')->build(),
                A::parameter('$notAReference')->build()
            )
            ->build()
        ;
        $codebase = new Codebase();
        $codebase->add($class);
        $resolver = new ExternalAssociationsResolver();

        $resolver->resolve($codebase);

        $this->assertCount(4, $codebase->definitions());
        $this->assertTrue($codebase->has(new Name('ReferenceA')));
        $this->assertTrue($codebase->has(new Name('ReferenceB')));
        $this->assertTrue($codebase->has(new Name('ReferenceC')));
    }

    /** @test */
    function it_adds_external_associations_from_constructor_parameters_with_union_types()
    {
        $class = A::class('TestClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$unionReference')->withType('ReferenceA|ReferenceB|ReferenceC|string|null')->build(),
                A::parameter('$referenceD')->withType('ReferenceD')->build(),
                A::parameter('$noType')->build(),
                A::parameter('$notAReference')->withType('int')->build()
            )
            ->build();
        $codebase = new Codebase();
        $codebase->add($class);
        $resolver = new ExternalAssociationsResolver();

        $resolver->resolve($codebase);

        $this->assertCount(5, $codebase->definitions());
        $this->assertTrue($codebase->has(new Name('ReferenceA')));
        $this->assertTrue($codebase->has(new Name('ReferenceB')));
        $this->assertTrue($codebase->has(new Name('ReferenceC')));
        $this->assertTrue($codebase->has(new Name('ReferenceD')));
    }

    /** @test */
    function it_removes_the_suffix_from_array_references()
    {
        $class = A::class('TestClass')
            ->withAPublicMethod(
                '__construct',
                A::parameter('$references')->withType('Reference[]')->build()
            )
            ->build();
        $codebase = new Codebase();
        $codebase->add($class);
        $resolver = new ExternalAssociationsResolver();

        $resolver->resolve($codebase);

        $this->assertCount(2, $codebase->definitions());
        $this->assertTrue($codebase->has($class->name()));
        $this->assertTrue($codebase->has(new Name('Reference')));
    }
}
