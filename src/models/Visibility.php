<?php

class Visibility
{
    const PUBLIC = 1;
    const PRIVATE = 2;

    public static function getIntValue($strValue)
    {
        switch (strtoupper($strValue)) {
            case "PUBLIC":
                return self::PUBLIC;
            case "PRIVATE":
                return self::PRIVATE;
            default:
                throw new InvalidArgumentException('Invalid visibility value');
        }
    }
}