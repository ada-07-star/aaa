<?php

namespace App\Observers;

use App\Models\Idea;
use App\Models\IdeaLog;

class IdeaObserver
{

    public function updating(Idea $idea)
    {
        if ($idea->isDirty('description')) {
            $originalLength = mb_strlen($idea->getOriginal('description'));
            $newLength = mb_strlen($idea->description);

            // محاسبه تفاوت کاراکتری
            $difference = abs($newLength - $originalLength);

            // اگر تفاوت ۵ کاراکتر یا بیشتر بود
            if ($difference >= 5) {
                $this->logDescriptionChange($idea, $originalLength, $newLength);
            }
        }
    }

    protected function logDescriptionChange(Idea $idea, $originalLength, $newLength)
    {
        IdeaLog::create([
            'idea_id' => $idea->id,
            'description' => $idea->getOriginal('description'),
        ]);
    }
}
