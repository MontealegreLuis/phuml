<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Members;

use RuntimeException;

final class UnsupportedType extends RuntimeException
{
    /** @param mixed $type */
    public static function declaredAs($type): UnsupportedType
    {
        return new self($type::class . ' is not yet a type supported by phUML');
    }
}
