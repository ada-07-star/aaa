<?php

namespace App\Observers;

use App\Models\Idea;
use App\Models\IdeaLog;

/**
 * @OA\Schema(
 *     schema="AutoIdeaLog",
 *     description="لاگ خودکار ایجاد شده توسط سیستم",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="idea_id", type="integer", example=15),
 *     @OA\Property(property="description", type="string", example="تغییرات خودکار: title به مقدار عنوان جدید تغییر کرد"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class IdeaObserver
{
    public function updated(Idea $idea)
    {
        $changes = $idea->getChanges();
        
        unset($changes['updated_at']);
        
        if (!empty($changes)) {
            IdeaLog::create([
                'idea_id' => $idea->id,
                'description' => 'تغییرات خودکار: ' . $this->formatChanges($changes)
            ]);
        }
    }
    
    protected function formatChanges(array $changes): string
    {
        $messages = [];
        foreach ($changes as $field => $value) {
            $messages[] = "$field به مقدار $value تغییر کرد";
        }
        
        return implode('، ', $messages);
    }
}