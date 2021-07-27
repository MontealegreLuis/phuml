<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Graphviz;

/**
 * `Node`s and `Edge`s can be represented in DOT language
 */
interface HasDotRepresentation
{
    public function dotTemplate(): string;
}
