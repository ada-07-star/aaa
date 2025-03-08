<?php

namespace App\Repositories;

use App\Interfaces\TopicRepositoryInterface;
use App\Models\Topic;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;

class TopicRepository implements TopicRepositoryInterface
{
    protected $model;

    public function __construct(Topic $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->model->with('categories')->get();
    }

    /**
     *
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $topic = $this->model->with('categories')->find($id);
        return $topic ? $topic->toArray() : null;
    }

    /**
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $topic = $this->model->create($data);
        return $topic->toArray();
    }

    /**
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->find($id)->update($data);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTopicsList(): Collection
    {
        $topics =  $this->model->with('categories')->orderBy('created_at', 'asc')->get();
        return $topics->map(function ($topic) {
            return [
                'id' => $topic->id,
                'category' => $topic->categories->isEmpty() ? null : [
                    'title' => $topic->categories->first()->title,
                    'id' => $topic->categories->first()->id,
                    'department_id' => $topic->categories->first()->department_id,
                    'description' => $topic->categories->first()->description,
                    'status' => $topic->categories->first()->status,
                    'created_by' => $topic->categories->first()->created_by,
                    'updated_by' => $topic->categories->first()->updated_by,
                ],
                'title' => $topic->title,
                'status' => $topic->status,
            ];
        });
    }

    /**
     *
     * @param string $date
     * @return string|null
     */
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        return Jalalian::fromDateTime($date)->format('Y/m/d');
    }

    /**
     *
     * @param Topic $topic
     * @return array
     */
    private function buildSteps($topic)
    {
        return [
            [
                'title' => 'تاریخ شروع ثبت ایده',
                'date' => $this->formatDate($topic->submit_date_from),
                'isCurrent' => 'yes',
            ],
            [
                'title' => 'تاریخ پایان ثبت ایده',
                'date' => $this->formatDate($topic->submit_date_to),
                'isCurrent' => 'no',
            ],
            [
                'title' => 'تاریخ شروع داوری',
                'date' => $this->formatDate($topic->consideration_date_from),
                'isCurrent' => 'no',
            ],
            [
                'title' => 'تاریخ پایان داوری',
                'date' => $this->formatDate($topic->consideration_date_to),
                'isCurrent' => 'no',
            ],
            [
                'title' => 'تاریخ شروع برنامه‌ریزی',
                'date' => $this->formatDate($topic->plan_date_from),
                'isCurrent' => 'no',
            ],
            [
                'title' => 'تاریخ پایان برنامه‌ریزی',
                'date' => $this->formatDate($topic->plan_date_to),
                'isCurrent' => 'no',
            ],
        ];
    }

    /**
     *
     * @param int $id
     * @return array
     */
    public function getTopicDetails(int $id): array
    {
        $topic = $this->model->with('categories')->find($id);

        if (!$topic) {
            return [];
        }

        return [
            'id' => $topic->id,
            'title' => $topic->title,
            'department_id' => $topic->department_id,
            'language_id' => $topic->language_id,
            'age_range' => $topic->age_range,
            'gender' => $topic->gender,
            'thumb_image' => $topic->thumb_image,
            'cover_image' => $topic->cover_image,
            'submit_date_from' => $topic->submit_date_from,
            'submit_date_to' => $topic->submit_date_to,
            'consideration_date_from' => $topic->consideration_date_from,
            'consideration_date_to' => $topic->consideration_date_to,
            'plan_date_from' => $topic->plan_date_from,
            'plan_date_to' => $topic->plan_date_to,
            'current_state' => $topic->current_state,
            'judge_number' => $topic->judge_number,
            'minimum_score' => $topic->minimum_score,
            'status' => $topic->status,
            'is_archive' => $topic->is_archive,
            'created_by' => $topic->created_by,
            'updated_by' => $topic->updated_by,
            'category' => $topic->categories->isEmpty() ? null : [
                'title' => $topic->categories->first()->title,
                'id' => $topic->categories->first()->id,
                'department_id' => $topic->categories->first()->department_id,
                'description' => $topic->categories->first()->description,
                'status' => $topic->categories->first()->status,
                'created_by' => $topic->categories->first()->created_by,
                'updated_by' => $topic->categories->first()->updated_by,
            ],
            'steps' => $this->buildSteps($topic),
            'totalIdea' => 2500,
            'acceptedIdea' => 100,
            'gender' => 'both',
            'age' => 'all',
            'commentCount' => 120,
            'viewCount' => 15000,
            'shareCount' => 100,
        ];
    }
}
