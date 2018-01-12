<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Builders;

use PhpParser\Node\Stmt\Class_;

class ClassBuilder
{
    /** @var AttributesBuilder */
    private $attributesBuilder;

    /** @var MethodsBuilder */
    private $methodsBuilder;

    public function __construct(
        AttributesBuilder $attributesBuilder = null,
        MethodsBuilder $methodsBuilder = null
    ) {
        $this->attributesBuilder = $attributesBuilder ?? new AttributesBuilder();
        $this->methodsBuilder = $methodsBuilder ?? new MethodsBuilder();
    }

    public function build(Class_ $class): array
    {
        return [
            'class' => $class->name,
            'attributes' => $this->attributesBuilder->build($class->stmts),
            'functions' => $this->methodsBuilder->build($class),
            'implements' => $this->buildInterfaces($class->implements),
            'extends' => !empty($class->extends) ? end($class->extends->parts) : null,
        ];
    }

    private function buildInterfaces(array $implements): array
    {
        $interfaces = [];
        /** @var \PhpParser\Node\Name $name */
        foreach ($implements as $name) {
            $interfaces[] = $name->getLast();
        }
        return $interfaces;
    }
}
