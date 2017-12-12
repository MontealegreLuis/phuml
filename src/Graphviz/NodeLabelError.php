<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

use Exception;
use RuntimeException;

class NodeLabelError extends RuntimeException
{
    public function __construct(Exception $e)
    {
        parent::__construct($e);
    }
}
