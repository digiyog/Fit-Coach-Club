<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\API\CronjobController;

class SendPendingPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:pending-amount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send pending payment reminder notifications to members with due amount';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting pending payment reminder notifications...');

        try {
            $controller = app(CronjobController::class);
            $response = $controller->pendingAmount();
            $data = $response->getData(true);

            if (!empty($data['_status'])) {
                $this->info($data['_message'] ?? 'Payment reminders sent successfully.');
                return 0;
            } else {
                $this->error($data['_message'] ?? 'Failed to send payment reminders.');
                return 1;
            }
        } catch (\Throwable $e) {
            $this->error('Exception: ' . $e->getMessage());
            \Log::error('Command cron:pending-amount failed: ' . $e->getMessage());
            return 1;
        }
    }
}
