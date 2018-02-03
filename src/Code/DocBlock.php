<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

abstract class DocBlock
{
    /** @var string */
    protected $comment;

    protected function __construct(?string $text)
    {
        $this->comment = $text;
    }
}
