<?php

namespace App\Models\Scopes;

trait AppointmentScopes
{
    public function scopeDates($query, array $dates)
    {
        if(empty($dates)) return;

        return $query->whereBetween('date', $dates);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}