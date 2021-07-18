<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

use RuntimeException;

/**
 * It is thrown when either the `dot` or `neato` commands fail
 */
final class ImageGenerationFailure extends RuntimeException
{
    public static function withOutput(string $errorMessage): ImageGenerationFailure
    {
        return new ImageGenerationFailure("Execution of external program failed:\n{$errorMessage}");
    }
}
