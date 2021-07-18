<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code;

use PHPUnit\Framework\TestCase;
use PhUml\Code\Codebase;
use PhUml\Code\Name;
use PhUml\TestBuilders\A;

class ExternalDefinitionsResolverTest extends TestCase
{
    /** @test */
    function it_does_not_change_the_definitions_if_no_relations_are_declared()
    {
        $codebase = new Codebase();
        $resolver = new ExternalDefinitionsResolver();

        $codebase->add(A::classNamed('AClass'));
        $codebase->add(A::classNamed('AnotherClass'));
        $codebase->add(A::interfaceNamed('AnInterface'));
        $codebase->add(A::interfaceNamed('AnotherInterface'));

        $resolver->resolve($codebase);

        $this->assertCount(4, $codebase->definitions());
    }

    /** @test */
    function it_adds_external_interfaces()
    {
        $codebase = new Codebase();
        $resolver = new ExternalDefinitionsResolver();

        $codebase->add(A::class('AClass')
            ->implementing(new Name('AnExternalInterface'), new Name('AnExistingInterface'))
            ->build());
        $codebase->add(A::interface('AnInterface')
            ->extending(new Name('AnotherExternalInterface'))->build());
        $codebase->add(A::interface('AnExistingInterface')->build());

        $resolver->resolve($codebase);

        $this->assertCount(5, $codebase->definitions());
        $this->assertArrayHasKey('AnExternalInterface', $codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalInterface', $codebase->definitions());
    }

    /** @test */
    function it_adds_external_classes()
    {
        $codebase = new Codebase();
        $resolver = new ExternalDefinitionsResolver();

        $codebase->add(A::class('AClass')->extending(new Name('AnExternalClass'))->build());
        $codebase->add(A::class('AnotherClass')->extending(new Name('AnotherExternalClass'))->build());

        $resolver->resolve($codebase);

        $this->assertCount(4, $codebase->definitions());
        $this->assertArrayHasKey('AnExternalClass', $codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalClass', $codebase->definitions());
    }

    /** @test */
    function it_adds_external_traits()
    {
        $codebase = new Codebase();
        $resolver = new ExternalDefinitionsResolver();

        $codebase->add(A::class('AClass')
            ->using(new Name('AnExternalTrait'), new Name('AnExistingTrait'))
            ->build());
        $codebase->add(A::trait('ATrait')
            ->using(new Name('AnotherExternalTrait'))->build());
        $codebase->add(A::trait('AnExistingTrait')->build());

        $resolver->resolve($codebase);

        $this->assertCount(5, $codebase->definitions());
        $this->assertArrayHasKey('AnExternalTrait', $codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalTrait', $codebase->definitions());
    }
}
