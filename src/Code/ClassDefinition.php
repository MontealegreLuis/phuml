<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use PhUml\Graphviz\HasNodeIdentifier;
use PhUml\Graphviz\ObjectHashIdentifier;

class ClassDefinition implements HasNodeIdentifier
{
    use ObjectHashIdentifier;

    /** @var string */
    public $name;

    /** @var Attribute[] */
    public $attributes;

    /** @var Method[] */
    public $functions;

    /** @var InterfaceDefinition[] */
    public $implements;

    /** @var ClassDefinition */
    public $extends;

    public function __construct(
        string $name,
        array $attributes = [],
        array $functions = [],
        array $implements = [],
        $extends = null
    ) {
        $this->name = $name;
        $this->attributes = $attributes;
        $this->functions = $functions;
        $this->implements = $implements;
        $this->extends = $extends;
    }

    public function hasConstructor(): bool
    {
        return \count(array_filter($this->functions, function (Method $function) {
            return $function->isConstructor();
        })) === 1;
    }

    /** @return Variable[] */
    public function constructorParameters(): array
    {
        if (!$this->hasConstructor()) {
            return [];
        }

        $constructors = array_filter($this->functions, function (Method $function) {
            return $function->isConstructor();
        });

        return reset($constructors)->params;
    }

    public function hasParent(): bool
    {
        return $this->extends !== null;
    }
}
