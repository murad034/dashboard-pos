<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DeleteLocalFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath; //needs real path to file

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('In DeleteLocalFileJob');

        try {

            $filePath = $this->filePath;

            unlink($filePath);

            Log::info('DeleteLocalFileJob Successfully deleted file with path: '.$filePath);

        } catch (\Throwable $th) {
            //throw $th;

            Log::error('DeleteLocalFileJob has error: '.$th->getMessage());
        }
    }
}
