<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Generators;

use RuntimeException;

class ClassDiagramConfiguration
{
    /** @var bool */
    private $extractAssociations;

    /** @var string */
    private $imageProcessor;

    /** @var bool */
    private $searchRecursively;

    public static function with(array $input): ClassDiagramConfiguration
    {
        return new ClassDiagramConfiguration(
            $input['recursive'],
            $input['associations'],
            $input['processor']
        );
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function isDotProcessor(): bool
    {
        return $this->imageProcessor === 'dot';
    }

    private function __construct(
        bool $searchRecursively,
        bool $extractAssociations,
        ?string $imageProcessor
    ) {
        $this->searchRecursively = $searchRecursively;
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

    public function searchRecursively(): bool
    {
        return $this->searchRecursively;
    }
}
