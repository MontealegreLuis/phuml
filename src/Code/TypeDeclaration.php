<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class TypeDeclaration
{
    /** @var string[] All valid types for PHP 7.1 */
    private static $builtInTypes = [
        'int', 'bool', 'string', 'array', 'float', 'callable', 'iterable'
    ];

    /** @var string */
    private $name;

    public function __construct(?string $name)
    {
        $this->name = $name;
    }

    public function isPresent(): bool
    {
        return $this->name !== null;
    }

    /**
     * This will help when building the relationships between classes/interfaces since built-in
     * types are not part of a UML class diagram
     */
    public function isBuiltIn(): bool
    {
        return !empty($this->name) && in_array($this->name, self::$builtInTypes, true);
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
