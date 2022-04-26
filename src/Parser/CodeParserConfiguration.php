<?php declare(strict_types=1);
/**
 * PHP version 8.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Parser;

use Webmozart\Assert\Assert;

final class CodeParserConfiguration
{
    /** @var string */
    private const ASSOCIATIONS = 'associations';

    /** @var string */
    private const HIDE_PRIVATE = 'hide-private';

    /** @var string */
    private const HIDE_PROTECTED = 'hide-protected';

    /** @var string */
    private const HIDE_PROPERTIES = 'hide-attributes';

    /** @var string */
    private const HIDE_METHODS = 'hide-methods';

    private readonly bool $extractAssociations;

    private readonly bool $hideProtected;

    private readonly bool $hidePrivate;

    private readonly bool $hideAttributes;

    private readonly bool $hideMethods;

    public static function defaultConfiguration(): CodeParserConfiguration
    {
        return new CodeParserConfiguration([
            self::ASSOCIATIONS => false,
            self::HIDE_PRIVATE => false,
            self::HIDE_PROTECTED => false,
            self::HIDE_PROPERTIES => false,
            self::HIDE_METHODS => false,
        ]);
    }

    /** @param mixed[] $options */
    public function __construct(array $options)
    {
        Assert::boolean($options[self::ASSOCIATIONS], 'Extract associations option must be a boolean value');
        $this->extractAssociations = $options[self::ASSOCIATIONS];
        Assert::boolean($options[self::HIDE_PRIVATE], 'Hide private members option must be a boolean value');
        $this->hidePrivate = $options[self::HIDE_PRIVATE];
        Assert::boolean($options[self::HIDE_PROTECTED], 'Hide protected members option must be a boolean value');
        $this->hideProtected = $options[self::HIDE_PROTECTED];
        Assert::boolean($options[self::HIDE_PROPERTIES], 'Hide attributes option must be a boolean value');
        $this->hideAttributes = $options[self::HIDE_PROPERTIES];
        Assert::boolean($options[self::HIDE_METHODS], 'Hide methods option must be a boolean value');
        $this->hideMethods = $options[self::HIDE_METHODS];
    }

    public function extractAssociations(): bool
    {
        return $this->extractAssociations;
    }

    public function hidePrivate(): bool
    {
        return $this->hidePrivate;
    }

    public function hideProtected(): bool
    {
        return $this->hideProtected;
    }

    public function hideAttributes(): bool
    {
        return $this->hideAttributes;
    }

    public function hideMethods(): bool
    {
        return $this->hideMethods;
    }
}
