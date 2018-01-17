<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use RuntimeException;
use Throwable;

/**
 * Custom exception to catch problems related to Twig
 */
class TemplateFailure extends RuntimeException
{
    public function __construct(Throwable $e)
    {
        parent::__construct($e);
    }
}
