<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface LogRepository extends RepositoryInterface
{
    public function addLog($userId, $operation, $targetType = null, $targetId = null);
}
