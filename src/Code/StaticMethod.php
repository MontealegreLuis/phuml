<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class StaticMethod extends Method
{
    public function __construct(string $name, Visibility $modifier, array $parameters = [])
    {
        parent::__construct($name, $modifier, $parameters);
        $this->isStatic = true;
    }
}
