<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;

trait StatusToggleable 
{
    /**
     * Toggle status.
     */
    public static function toggleStatus($ids)
    {
        // Get table name
        $table = with(new self)->getTable();
        //---------------

        $active_status   = config('constants.statuses.ACTIVE.value');
        $inactive_status = config('constants.statuses.INACTIVE.value');

        $ids = implode(', ', $ids);

        return DB::statement(
            DB::raw(
                // "UPDATE {$table} SET status = (CASE WHEN status = CAST('{$active_status}' AS BOOLEAN) THEN CAST('{$inactive_status}' AS BOOLEAN) ELSE CAST('{$active_status}' AS BOOLEAN) END) WHERE id IN ({$ids})"
                "UPDATE {$table} SET status = (CASE WHEN status = '{$active_status}' THEN '{$inactive_status}' ELSE '{$active_status}' END) WHERE id IN ({$ids})"
            )
        );
    }
}
