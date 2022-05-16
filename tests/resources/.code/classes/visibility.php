<?php
namespace phuml;

enum plVisibility implements plHasValue
{
    use plWithValue;

    private const PACKAGE = "NOT_SUPPORTED";

    case PUBLIC;
    case PRIVATE;
    case PROTECTED;

    public static function all(): array
    {
        return [self::PUBLIC, self::PRIVATE, self::PROTECTED];
    }

    public function toString(): string
    {
        return $this->name;
    }
}
