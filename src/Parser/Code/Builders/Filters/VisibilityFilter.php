<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Filters;

use PhpParser\Node\Param;
use PhpParser\Node\Stmt;

interface VisibilityFilter
{
    public function accept(Stmt|Param $member): bool;
}
