<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use PhUml\Code\Name;
use PhUml\Code\Variables\TypeDeclaration;
use Stringable;

final class EdgeKey implements Stringable
{
    private readonly string $key;

    public static function from(Name $name, TypeDeclaration $type): EdgeKey
    {
        return new EdgeKey($name . $type);
    }

    private function __construct(string $key)
    {
        $this->key = $key;
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
