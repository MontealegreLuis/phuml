<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

/**
 * It represents the doc block of either a method or an attribute
 */
abstract class DocBlock
{
    /** @var string */
    protected $comment;

    protected function __construct(?string $comment)
    {
        $this->comment = $comment;
    }
}
