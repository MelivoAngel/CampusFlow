<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command(

    'schedule:sync-status'

)->everyMinute();