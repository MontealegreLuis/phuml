<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Configuration;

use PhUml\Processors\ImageProcessorName;

final class ClassDiagramConfiguration
{
    private ImageProcessorName $imageProcessor;

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        $this->setImageProcessor($input['processor'] ?? '');
    }

    public function isDotProcessor(): bool
    {
        return $this->imageProcessor->isDot();
    }

    private function setImageProcessor(?string $imageProcessor): void
    {
        $this->imageProcessor = new ImageProcessorName($imageProcessor);
    }
}
