<?php

namespace App\Services;

use App\Repositories\LogRepositoryEloquent;

class LogService
{
    protected $logRepository;
    public function __construct(LogRepositoryEloquent $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function list()
    {
        return $this->logRepository->orderBy('created_at', 'desc')->paginate();
    }

    public function addLog($userId, $operation, $target = null, $targetId = null){
        return $this->logRepository->addLog($userId, $operation, $target, $targetId);
    }
}