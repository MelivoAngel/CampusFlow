<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Domain\Schedules\Services\SyncScheduleStatusService;

class SyncScheduleStatusCommand extends Command
{
    protected $signature =
        'schedule:sync-status';

    protected $description =
        'Sync reservation statuses automatically';

    public function handle(
        SyncScheduleStatusService $service
    ): void
    {
        $service->sync();

        $this->info(

            'Schedule statuses synced successfully.'
        );
    }
}