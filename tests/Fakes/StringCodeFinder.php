<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Fakes;

use PhUml\Parser\NonRecursiveCodeFinder;

class StringCodeFinder extends NonRecursiveCodeFinder
{
    public function __construct()
    {
        $this->files = [];
    }

    public function add(string $definition)
    {
        $this->files[] = $definition;
    }
}
