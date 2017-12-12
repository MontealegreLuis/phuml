<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

class plClassNameLabelBuilder extends plNodeLabelBuilder
{
    public function __construct()
    {
    }

    public function labelForClass(plPhpClass $class): string
    {
        return $this->template($class);
    }

    public function labelForInterface(plPhpInterface $interface): string
    {
        return $this->template($interface);
    }

    private function template($classOrInterface): string
    {
        return "<<table><tr><td>{$classOrInterface->name}</td></tr></table>>";
    }
}
