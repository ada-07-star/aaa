<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department_id' => 'sometimes|integer',
            'language_id' => 'sometimes|integer',
            'status' => 'sometimes|integer',
            'category_id' => 'sometimes|integer',
            'keyword' => 'sometimes|string',
            'sort_by' => 'sometimes|in:created_at,updated_at,title',
            'sort_direction' => 'sometimes|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100'
        ];
    }
}
