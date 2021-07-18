<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

use BadMethodCallException;
use PhUml\Code\Name;
use Stringable;

/**
 * It represents a variable declaration
 */
final class Variable implements HasType, Stringable
{
    use WithTypeDeclaration;

    public function __construct(private string $name, TypeDeclaration $type)
    {
        $this->type = $type;
    }

    /**
     * References to arrays need to have the `[]` removed from their names in order to create
     * external definitions with a proper name
     *
     * The edges created from these references need to map to the names without the suffix
     *
     * @see \PhUml\Parser\Code\ExternalAssociationsResolver::resolveExternalAttributes()
     * @see \PhUml\Parser\Code\ExternalAssociationsResolver::resolveExternalConstructorParameters()
     * @see \PhUml\Graphviz\Builders\EdgesBuilder::addAssociation()
     */
    public function referenceName(): Name
    {
        $name = $this->isArray() ? $this->arrayTypeName() : $this->typeName();
        if ($name === null) {
            throw new BadMethodCallException('This attribute is not a reference to a code definition');
        }
        return $name;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s',
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }

    private function typeName(): ?Name
    {
        return $this->type->name();
    }

    private function isArray(): bool
    {
        return $this->type->isArray();
    }

    private function arrayTypeName(): Name
    {
        return new Name($this->type->removeArraySuffix());
    }
}
