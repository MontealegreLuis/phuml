<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Attributes;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use PhUml\Code\Variables\TypeDeclaration;

final class ConstantTest extends TestCase
{
    /** @test */
    function its_type_cannot_be_a_reference_to_a_definition_since_constants_must_have_built_in_types()
    {
        $constant = new Constant('A_CONSTANT', TypeDeclaration::from('string'));

        $this->expectException(BadMethodCallException::class);
        $constant->referenceName();
    }
}
