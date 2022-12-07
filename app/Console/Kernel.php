<?php

namespace App\Console;

use App\Models\DeliveryLog;
use App\Models\KeypadLayout;
use App\Models\MarketingCampaign;
use App\Models\PriceDraft;
use App\Models\ReceiptDesigner;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SaveDraft::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            ReceiptDesigner::transformPassedDraftToLiveRecord();
            PriceDraft::transformPassedDraftToPricing();
            KeypadLayout::transformPassedDraftToLiveRecord();
            DeliveryLog::checkOpens();
        })->everyFiveMinutes();
        $schedule->call(function () {
            MarketingCampaign::sendBulkEmailSchedule();
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
