<?php

namespace App\Console\Commands;

use App\Models\Config;
use Illuminate\Console\Command;

class SaveDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.

     */
    public function handle()
    {
        Config::create(array("app_name" => 'adfdsa'));
        \Log::info("Cron is working fine!");
    }
}
