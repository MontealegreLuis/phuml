<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhUml\Code;

class Attribute extends Variable
{
    private static $symbols = [
        'private' => '-',
        'public' => '+',
        'protected' => '#',
    ];

    /** @var string */
    public $modifier;

    public function __construct(string $name, string $modifier = 'public', string $type = null)
    {
        parent::__construct($name, $type);
        $this->modifier = $modifier;
    }

    /**
     * It doesn't currently support information type
     *
     * @see plGraphvizProcessor#getClassDefinition In its original version
     */
    public function __toString()
    {
        return sprintf('%s%s', self::$symbols[$this->modifier], $this->name);
    }
}
