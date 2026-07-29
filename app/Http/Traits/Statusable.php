<?php

namespace App\Http\Traits;

trait Statusable 
{
    /**
     * Scope a query to only include active records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, $column = 'status')
    {
        return $query->where($column, config('constants.statuses.ACTIVE.value'));
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('status', config('constants.statuses.INACTIVE.value'));
    }

    /**
     * Get the is active attribute.
     *
     * @return string
     */
    public function getIsActiveAttribute()
    {
        return $this->status == config('constants.statuses.ACTIVE.value');
    }
}
