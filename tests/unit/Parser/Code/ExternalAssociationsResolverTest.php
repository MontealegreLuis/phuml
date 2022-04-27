<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\TestBuilders\A;

final class ExternalAssociationsResolverTest extends ExternalDefinitionsResolverTest
{
    /** @test */
    function it_adds_external_properties()
    {
        $class = A::class('TestClass')
            ->withAPrivateProperty('$referenceA', 'ReferenceA')
            ->withAPrivateProperty('$referenceB', 'ReferenceB')
            ->withAPrivateProperty('$referenceC', 'ReferenceC')
            ->withAPrivateProperty('$notAReference', 'int')
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
    function it_adds_external_associations_from_properties_with_union_types()
    {
        $class = A::class('TestClass')
            ->withAPrivateProperty('$unionReferences', 'ReferenceA|ReferenceB|ReferenceC|string|null')
            ->withAPrivateProperty('$referenceD', 'ReferenceD')
            ->withAPrivateProperty('$noType')
            ->withAPrivateProperty('$notAReference', 'int')
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
