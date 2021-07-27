<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Variables;

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

    /** @return Name[] */
    public function references(): array
    {
        return $this->type->references();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s',
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }
}
