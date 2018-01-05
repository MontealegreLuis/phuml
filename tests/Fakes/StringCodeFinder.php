<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace PhUml\Fakes;

use PhUml\Parser\CodeFinder;

class StringCodeFinder extends CodeFinder
{
    /** @var string[] */
    protected $files;

    public function __construct()
    {
        $this->files = [];
    }

    public function add(string $definition)
    {
        $this->files[] = $definition;
    }

    public function files(): array
    {
        return $this->files;
    }
}
