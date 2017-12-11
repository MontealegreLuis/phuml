<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

trait plObjectHashIdentifier
{
    public function identifier(): string
    {
        return spl_object_hash($this);
    }
}
