<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents the doc block of either a method or an attribute
 */
abstract class DocBlock
{
    protected ?string $comment;

    protected function __construct(?string $comment)
    {
        $this->comment = $comment;
    }
}
