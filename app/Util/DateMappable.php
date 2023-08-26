<?php

namespace App\Util;

trait DateMappable
{
    /**
     * Get formatted dates from the default columns such as created_at, deleted_at, updated_at
     */
    public function default()
    {
        return [
            'deleted_at' => $this?->deleted_at,
            'created_at' => $this?->created_at,
            'last_updated' => $this?->updated_at,
            'created_at_diff_date' => $this?->created_at?->diffForHumans(),
            'created_at_nice_format' => $this?->created_at?->format("F d, Y"),
            'created_at_db_date' => $this?->created_at?->format("Y-m-d"),
            'updated_at_diff_date' => $this?->updated_at?->diffForHumans(),
        ];
    }
}
