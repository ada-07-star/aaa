<?php

namespace App\Interfaces;

interface IdeaLogsRepositoryInterface
{
    public function getAllIdeaLogs(array $filters = [], string $sort = 'created_at');
    public function createIdeaLog($data);
    public function findById($ideaLogId);
    public function updateIdeaLog(array $data, $ideaLogId);
    public function deleteIdeaLog($ideaLogId);
} 