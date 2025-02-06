<?php

namespace App\Enums;

enum TodoPriorities: string
{
    case HIGH = 'High';
    case MEDIUM = 'Medium';
    case LOW = 'Low';

    /**
     * Get all values
     *
     * @return array
     */
    public static function getAllValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
