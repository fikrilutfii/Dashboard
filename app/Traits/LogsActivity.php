<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('Created', $model->getAttributes(), []);
        });

        static::updated(function ($model) {
            $model->logActivity('Updated', $model->getAttributes(), $model->getOriginal());
        });

        static::deleted(function ($model) {
            $model->logActivity('Deleted', [], $model->getOriginal());
        });
    }

    protected function logActivity($action, $newAttributes = [], $oldAttributes = [])
    {
        // Don't log if no changes during update
        if ($action === 'Updated' && empty(array_diff_assoc($newAttributes, $oldAttributes))) {
            return;
        }

        $userId = Auth::id() ?? 1; // Fallback to 1 if from console/seeder (or null if preferred)

        $className = class_basename(static::class);
        $displayName = method_exists($this, 'getLogDisplayName') ? $this->getLogDisplayName() : $className;

        $description = "{$action} {$displayName}";

        ActivityLog::create([
            'user_id' => $userId,
            'activity' => $action,
            'description' => $description,
            'subject_type' => static::class,
            'subject_id' => $this->id,
            'properties' => json_encode([
                'old' => $oldAttributes,
                'new' => $newAttributes
            ]),
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'System',
        ]);
    }
}
