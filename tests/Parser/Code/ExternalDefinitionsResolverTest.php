<?php
/**
 * PHP version 7.1
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
            ->implementing(Name::from('AnExternalInterface'), Name::from('AnExistingInterface'))
            ->build());
        $codebase->add(A::interface('AnInterface')
            ->extending(Name::from('AnotherExternalInterface'))->build());
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

        $codebase->add(A::class('AClass')->extending(Name::from('AnExternalClass'))->build());
        $codebase->add(A::class('AnotherClass')->extending(Name::from('AnotherExternalClass'))->build());

        $resolver->resolve($codebase);

        $this->assertCount(4, $codebase->definitions());
        $this->assertArrayHasKey('AnExternalClass', $codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalClass', $codebase->definitions());
    }
}
