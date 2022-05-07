<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders;

enum TagName: string
{
    case VAR = 'var';
    case RETURN = 'return';
    case PARAM = 'param';
}
