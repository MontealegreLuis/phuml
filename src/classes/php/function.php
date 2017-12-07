<?php

class plPhpFunction
{
    private static $symbols = [
        'private' => '-',
        'protected' => '#',
        'public' => '+',
    ];

    /** @var string */
    public $name;

    /** @var string */
    public $modifier;

    /** @var plPhpFunctionParameter[] */
    public $params;

    public function __construct(string $name, string $modifier = 'public', array $params = [])
    {
        $this->name = $name;
        $this->modifier = $modifier;
        $this->params = $params;
    }

    public function isConstructor(): bool
    {
        return $this->name === '__construct';
    }

    public function __toString()
    {
        return sprintf(
            '%s%s%s',
            self::$symbols[$this->modifier],
            $this->name,
            empty($this->params) ? '()' : '( ' . implode($this->params, ', ') . ' )'
        );
    }
}
