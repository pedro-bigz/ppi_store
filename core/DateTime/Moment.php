<?php namespace Core\DateTime;

use DateTime;
use DateTimeInterface;

class Moment extends DateTime implements DateTimeInterface
{
    public const FORMAT = 'Y-m-d H:i:s';

    public static function create($date, $tz = null)
    {
        return new static($date, $tz);
    }

    public static function now($tz = null)
    {
        return new static(null, $tz);
    }

    public static function isDate($value)
    {
        return $value instanceof DateTimeInterface;
    }
}