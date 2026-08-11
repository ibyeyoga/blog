<?php

namespace App\Repositories;

use App\Criteria\LogListCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PostRepository;
use App\Models\Log;

class LogRepositoryEloquent extends BaseRepository implements PostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Log::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->pushCriteria(app(LogListCriteria::class));
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
