<?php

namespace App\Domain\Schedules\Enums;

enum ScheduleStatus: string
{
    case UPCOMING = 'upcoming';

    case ONGOING = 'ongoing';

    case COMPLETED = 'completed';

    case RESCHEDULED = 'rescheduled';

    case CANCELLED = 'cancelled';
}