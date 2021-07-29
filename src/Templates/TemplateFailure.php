<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Templates;

use RuntimeException;
use Throwable;

/**
 * Custom exception to catch problems related to Twig
 */
final class TemplateFailure extends RuntimeException
{
    public function __construct(Throwable $cause)
    {
        parent::__construct("Template rendering failed: {$cause->getMessage()}", $cause->getCode(), $cause);
    }
}
