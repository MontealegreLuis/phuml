<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code\Methods;

use PhUml\Code\Modifiers\Visibility;
use PhUml\Code\Variables\TypeDeclaration;

class AbstractMethod extends Method
{
    /** @param \PhUml\Code\Variables\Variable[] $parameters */
    public function __construct(
        string $name,
        Visibility $modifier,
        TypeDeclaration $returnType,
        array $parameters = []
    ) {
        parent::__construct($name, $modifier, $returnType, $parameters);
        $this->isAbstract = true;
    }
}
