<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;
use App\Models\AttendanceLogs;
use Illuminate\Support\Facades\DB;

class ReconcileAttendanceDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:reconcile-days {--user_id= : Reconcile only a specific user ID} {--dry-run : Preview changes without applying them}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean duplicate attendance records and reconcile users.days balance based on unique attended dates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->option('user_id');
        $isDryRun = $this->option('dry-run');

        $this->info($isDryRun ? '=== RECONCILING ATTENDANCE DAYS (DRY RUN) ===' : '=== RECONCILING ATTENDANCE DAYS ===');

        $userQuery = User::where('role_type', 'user')->whereNull('deleted_at');
        if ($userId) {
            $userQuery->where('id', $userId);
        }

        $users = $userQuery->orderBy('id', 'asc')->get();
        if ($users->isEmpty()) {
            $this->warn('No users found matching criteria.');
            return 0;
        }

        $totalUsers = $users->count();
        $this->info("Found {$totalUsers} user(s) to process.");

        $totalDuplicatesRemoved = 0;
        $usersModified = 0;
        $summaryTable = [];

        foreach ($users as $user) {
            $oldDays = (int) $user->days;

            // 1. Find duplicate attendance records on the same date
            $duplicateDates = Attendance::select('date', DB::raw('COUNT(*) as cnt'))
                ->where('user_id', $user->id)
                ->where('type', 2)
                ->whereNull('deleted_at')
                ->groupBy('date')
                ->having('cnt', '>', 1)
                ->get();

            $duplicatesRemovedForUser = 0;

            foreach ($duplicateDates as $dupGroup) {
                $records = Attendance::where('user_id', $user->id)
                    ->where('type', 2)
                    ->whereNull('deleted_at')
                    ->whereDate('date', $dupGroup->date)
                    ->orderBy('id', 'asc')
                    ->get();

                // Keep first, soft-delete subsequent duplicates
                $toDelete = $records->slice(1);
                foreach ($toDelete as $dupRecord) {
                    $duplicatesRemovedForUser++;
                    if (!$isDryRun) {
                        $dupRecord->delete();
                    }
                }
            }

            $totalDuplicatesRemoved += $duplicatesRemovedForUser;

            // 2. Count unique attended dates
            $uniqueAttendedDates = Attendance::where('user_id', $user->id)
                ->where('type', 2)
                ->whereNull('deleted_at')
                ->distinct('date')
                ->count('date');

            // 3. Recalculate pending days
            $newDays = $user->recalculatePendingDays();
            if (!$isDryRun) {
                // Update the most recent attendance_log total_days to stay in sync
                $lastLog = AttendanceLogs::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                if ($lastLog && $lastLog->total_days != $newDays) {
                    $lastLog->update(['total_days' => $newDays]);
                }
            } else {
                // Revert user object in dry-run
                $user->days = $oldDays;
            }

            $hasChanged = ($oldDays !== $newDays || $duplicatesRemovedForUser > 0);
            if ($hasChanged) {
                $usersModified++;
                $summaryTable[] = [
                    'ID'                  => $user->id,
                    'Name'                => $user->name,
                    'Old Days'            => $oldDays,
                    'New Days'            => $newDays,
                    'Diff'                => ($newDays - $oldDays),
                    'Unique Dates'        => $uniqueAttendedDates,
                    'Duplicates Removed'  => $duplicatesRemovedForUser,
                ];
            }
        }

        if (!empty($summaryTable)) {
            $this->table(
                ['ID', 'Name', 'Old Days', 'New Days', 'Diff', 'Attended Dates', 'Dups Removed'],
                $summaryTable
            );
        } else {
            $this->info('All users are already in perfect sync!');
        }

        $this->info("Completed. Total users checked: {$totalUsers}. Users with adjustments: {$usersModified}. Duplicate attendance records removed: {$totalDuplicatesRemoved}.");

        return 0;
    }
}
