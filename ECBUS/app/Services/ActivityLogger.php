<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity to the database.
     *
     * @param string $action
     * @param string $description
     * @return \App\Models\ActivityLog|null
     */
    public static function log($action, $description)
    {
        if (!auth()->check()) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
