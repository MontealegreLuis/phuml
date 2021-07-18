<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser\Code\Builders\Filters;

use PhpParser\Node\Stmt\Nop;
use PHPUnit\Framework\TestCase;

final class PrivateVisibilityFilterTest extends TestCase
{
    /** @test */
    function it_excludes_statements_that_are_not_methods_or_constants_or_attributes()
    {
        $filter = new PrivateVisibilityFilter();

        $this->assertFalse($filter->accept(new Nop()));
    }
}
