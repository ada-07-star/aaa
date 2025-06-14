<?php

namespace App\Repositories;

use App\Interfaces\IdeaLogsRepositoryInterface;
use App\Models\IdeaLog;

/**
 * Class IdeaLogsRepository.
 */
class IdeaLogsRepository implements IdeaLogsRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    protected $ideaLog;
    public function __construct(IdeaLog $ideaLog)
    {
        $this->ideaLog = $ideaLog;
    }
    public function getAllIdeaLogs(array $filters = [], string $sort = 'created_at')
    {
        $query = $this->ideaLog->query();

        if (isset($filters['idea_id'])) {
            $query->where('idea_id', $filters['idea_id']);
        }

        return $query->orderBy($sort, 'desc')->get();
    }

    public function createIdeaLog($data)
    {
        return $this->ideaLog->create($data);
    }

    public function findById($ideaLogId)
    {
        return $this->ideaLog->find($ideaLogId);
    }

    public function updateIdeaLog(array $data, $ideaLogId)
    {
        return $this->ideaLog->find($ideaLogId)->update($data);
    }
    
    public function deleteIdeaLog($ideaLogId)
    {
        return $this->ideaLog->find($ideaLogId)->delete();
    }


}
