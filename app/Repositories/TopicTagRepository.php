<?php

namespace App\Repositories;

use App\Interfaces\TopicTagRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TopicTagRepository implements TopicTagRepositoryInterface
{

    public function getAllFilteredTopicTags(array $filters)
    {
        $query = DB::table('topic_tags');

        if (isset($filters['topic_id'])) {
            $query->where('topic_id', $filters['topic_id']);
        }

        if (isset($filters['tag_id'])) {
            $query->where('tag_id', $filters['tag_id']);
        }

        return $query->get();
    }

    public function createTopicTag(array $data): int
    {
        return DB::table('topic_tags')->insertGetId([
            'topic_id' => $data['topic_id'],
            'tag_id' => $data['tag_id'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
