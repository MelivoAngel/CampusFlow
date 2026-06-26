<?php

namespace App\Domain\Meters\Enums;

enum MeterResourceType: string
{
    case WATER = 'water';

    case ELECTRICITY = 'electricity';

}