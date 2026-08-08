<?php

namespace App\Http\Repositories;

class LogRepository 
{

    public function getLogs() {
        $query = \App\Models\Log::query();
        return $query->userName()->orderBy('created_at', 'desc')->paginate(10);
    }

    public function addLog($userId, $operation, $targetType = null, $targetId = null) {
        return \App\Models\Log::create([
            'user_id' => $userId,
            'operation' => $operation,
            'target_id' => $targetId,
            'target' => $targetType
        ]);
    }
}