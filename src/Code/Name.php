<?php declare(strict_types=1);
/**
 * PHP version 8.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

use Stringable;
use Webmozart\Assert\Assert;

final class Name implements Stringable
{
    /** @var string[]  */
    private array $parts;

    public function __construct(string $name)
    {
        Assert::notEmpty(trim($name), 'Definition name cannot be null or empty');
        $this->parts = explode('\\', trim($name));
    }

    public function first(): string
    {
        return $this->parts[0];
    }

    public function fullName(): string
    {
        return implode('\\', $this->parts);
    }

    public function isArray(): bool
    {
        return str_ends_with((string) $this, '[]');
    }

    public function removeArraySuffix(): string
    {
        return str_replace(search: '[]', replace: '', subject: $this->fullName());
    }

    public function __toString(): string
    {
        return $this->parts[count($this->parts) - 1];
    }

    public function packageName(): string
    {
        $package = array_slice($this->parts, 0, -1);
        return implode('\\', $package);
    }
}
