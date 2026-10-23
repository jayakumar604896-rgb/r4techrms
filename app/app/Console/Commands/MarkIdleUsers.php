<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkIdleUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:mark-idle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as idle if inactive for 15 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idleTime = Carbon::now()->subMinutes(15);

        $updated = User::where('last_activity', '<', $idleTime)
            ->where('user_status', 'active')
            ->update(['user_status' => 'idle']);

        $this->info("Idle users updated successfully. Total updated: $updated");

        Log::info('MarkIdleUsers command ran at ' . now() . ". Total users updated: $updated");
    }
}
