<?php

namespace App\Enum;

enum ReservationStatus : string
{
    case PENDING = 'Pending';
    case COMPLETE = 'Complete';
    case RESERVED = 'Reserved';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->name;
        }
        return $options;
    }
}
