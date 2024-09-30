<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
    case COMPLETED = 'completed';
}
