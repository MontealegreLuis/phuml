<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz\Builders;

use Exception;
use RuntimeException;

/**
 * Custom exception to catch problems related to Twig
 */
class NodeLabelError extends RuntimeException
{
    public function __construct(Exception $e)
    {
        parent::__construct($e);
    }
}
