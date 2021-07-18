<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use BadMethodCallException;
use PhUml\Code\Modifiers\HasVisibility;
use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Modifiers\WithVisibility;
use PhUml\Code\Name;
use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\WithTypeDeclaration;
use Stringable;

final class Constant implements HasType, HasVisibility, Stringable
{
    use WithTypeDeclaration;
    use WithVisibility;

    public function __construct(private string $name, TypeDeclaration $type, Visibility $visibility = null)
    {
        $this->type = $type;
        $this->modifier = $visibility ?? Visibility::public();
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s',
            $this->modifier,
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }

    public function referenceName(): Name
    {
        throw new BadMethodCallException('Constants must be built-in types');
    }
}
