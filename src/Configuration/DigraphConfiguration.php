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

    /** @var bool */
    private $hideProtected;

    /** @var bool */
    private $hidePrivate;

    public function __construct(array $input)
    {
        $this->searchRecursively = (bool)$input['recursive'];
        $this->extractAssociations = (bool)$input['associations'];
        $this->hidePrivate = (bool)$input['hide-private'];
        $this->hideProtected = (bool)$input['hide-protected'];
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
    }

    public function hidePrivate(): bool
    {
        return $this->hidePrivate;
    }

    public function hideProtected(): bool
    {
        return $this->hideProtected;
    }
}
