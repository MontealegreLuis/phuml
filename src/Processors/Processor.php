<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

/**
 * Every processor has a name
 */
interface Processor
{
    public function name(): string;
}
