<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use PhUml\Code\Variables\HasType;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\WithTypeDeclaration;

final class Constant implements HasType
{
    use WithTypeDeclaration;

    /** @var string */
    private $name;

    public function __construct(string $name, TypeDeclaration $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    public function __toString()
    {
        return sprintf(
            '+%s%s',
            $this->name,
            $this->type->isPresent() ? ": {$this->type}" : ''
        );
    }
}
