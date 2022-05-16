<?php declare(strict_types=1);
/**
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
        $this->codebase->add(A::class('AClass')
            ->implementing(new Name('AnExternalInterface'), new Name('AnExistingInterface'))
            ->build());
        $this->codebase->add(A::interface('AnInterface')
            ->extending(new Name('AnotherExternalInterface'))->build());
        $this->codebase->add(A::interface('AnExistingInterface')->build());
        $this->codebase->add(A::enum('AnEnum')->implementing(new Name('ThirdExternalInterface'))->build());

        $this->resolver->resolve($this->codebase);

        $this->assertCount(7, $this->codebase->definitions());
        $this->assertArrayHasKey('AnExternalInterface', $this->codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalInterface', $this->codebase->definitions());
        $this->assertArrayHasKey('ThirdExternalInterface', $this->codebase->definitions());
    }

    /** @test */
    function it_adds_external_classes()
    {
        $this->codebase->add(A::class('AClass')->extending(new Name('AnExternalClass'))->build());
        $this->codebase->add(A::class('AnotherClass')->extending(new Name('AnotherExternalClass'))->build());

        $this->resolver->resolve($this->codebase);

        $this->assertCount(4, $this->codebase->definitions());
        $this->assertArrayHasKey('AnExternalClass', $this->codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalClass', $this->codebase->definitions());
    }

    /** @test */
    function it_adds_external_traits()
    {
        $this->codebase->add(A::class('AClass')
            ->using(new Name('AnExternalTrait'), new Name('AnExistingTrait'))
            ->build());
        $this->codebase->add(A::trait('ATrait')
            ->using(new Name('AnotherExternalTrait'))->build());
        $this->codebase->add(A::trait('AnExistingTrait')->build());
        $this->codebase->add(A::enum('AnEnum')->using(new Name('ThirdExternalTrait'))->build());

        $this->resolver->resolve($this->codebase);

        $this->assertCount(7, $this->codebase->definitions());
        $this->assertArrayHasKey('AnExternalTrait', $this->codebase->definitions());
        $this->assertArrayHasKey('AnotherExternalTrait', $this->codebase->definitions());
        $this->assertArrayHasKey('ThirdExternalTrait', $this->codebase->definitions());
    }

    /** @before */
    function let()
    {
        $this->codebase = new Codebase();
        $this->resolver = new ExternalDefinitionsResolver();
    }

    private Codebase $codebase;

    private ExternalDefinitionsResolver $resolver;
}
