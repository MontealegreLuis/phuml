<?php declare(strict_types=1);
/**
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Processors;

final class OutputContent
{
    public function __construct(private readonly string $content)
    {
    }

    public function value(): string
    {
        return $this->content;
    }
}
