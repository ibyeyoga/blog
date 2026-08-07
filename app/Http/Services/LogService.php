<?php

namespace App\Http\Services;

use \App\Http\Repositories\LogRepository;
use \App\Enum\Operation;

class LogService
{
    protected $logRepository;
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function getLogs($keyword = null)
    {
        return $this->logRepository->getLogs();
    }

    public function addLog($userId, $operation, $target = null, $targetId = null){
        return $this->logRepository->addLog($userId, $operation, $target, $targetId);
    }
}