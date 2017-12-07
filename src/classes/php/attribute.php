<?php

class plPhpAttribute
{
    private static $symbols = [
        'private' => '-',
        'public' => '+',
        'protected' => '#',
    ];

    /** @var string */
    public $name;

    /** @var string */
    public $modifier;

    /** @var string */
    public $type;

    public function __construct(string $name, string $modifier = 'public', string $type = null)
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->type = $type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
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
