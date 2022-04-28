<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Modifiers;

/**
 * It represents the visibility of either a property or a method
 */
enum Visibility: string
{
    case PRIVATE = '-';
    case PUBLIC = '+';
    case PROTECTED = '#';
}
