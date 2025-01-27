<?php

namespace App\Enums;

enum DeletionLogStatus: string
{
    case PENDING = 'Pending';
    case REQUESTED = 'Requested';
    case COMPLETED = 'Completed';

}
