<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Actions;

use RuntimeException;

class ClassDiagramConfiguration
{
    /** @var bool */
    private $extractAssociations;

    /** @var string */
    private $imageProcessor;

    public static function from(array $input): ClassDiagramConfiguration
    {
        return new ClassDiagramConfiguration(
            $input['associations'],
            $input['processor']
        );
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function imageProcessor(): string
    {
        return $this->imageProcessor;
    }

    private function __construct(bool $extractAssociations, ?string $imageProcessor) {
        $this->extractAssociations = $extractAssociations;
        $this->setImageProcessor($imageProcessor);
    }

    private function setImageProcessor(?string $imageProcessor): void
    {
        if (!\in_array($imageProcessor, ['neato', 'dot'], true)) {
            throw new RuntimeException("Invalid processor '$imageProcessor' found, expected processors are neato and dot");
        }
        $this->imageProcessor = $imageProcessor;
    }
}
