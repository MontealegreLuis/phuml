<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;
use PhUml\Code\Variables\Variable;

final class StaticMethod extends Method
{
    /** @param Variable[] $parameters */
    public function __construct(
        string $name,
        Visibility $modifier,
        TypeDeclaration $returnType,
        array $parameters = []
    ) {
        parent::__construct($name, $modifier, $returnType, $parameters);
        $this->isStatic = true;
    }
}
