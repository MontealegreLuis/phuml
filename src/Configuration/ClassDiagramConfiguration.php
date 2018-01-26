<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use RuntimeException;

class ClassDiagramConfiguration extends DigraphConfiguration
{
    /** @var string */
    private $imageProcessor;

    public function __construct(array $input)
    {
        parent::__construct($input);
        $this->setImageProcessor($input['processor']);
    }

    public function isDotProcessor(): bool
    {
        return $this->imageProcessor === 'dot';
    }

    private function setImageProcessor(?string $imageProcessor): void
    {
        if (!\in_array($imageProcessor, ['neato', 'dot'], true)) {
            throw new RuntimeException("Invalid processor '$imageProcessor' found, expected processors are neato and dot");
        }
        $this->imageProcessor = $imageProcessor;
    }
}
