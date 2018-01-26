<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

class DigraphConfiguration
{
    /** @var bool */
    protected $searchRecursively;

    /** @var bool */
    protected $extractAssociations;

    public function __construct(array $input)
    {
        $this->searchRecursively = $input['recursive'];
        $this->extractAssociations = $input['associations'];
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
    }
}
