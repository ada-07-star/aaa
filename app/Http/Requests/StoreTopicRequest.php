<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopicRequest extends FormRequest
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
            'title' => 'required|string|max:500',
            'department_id' => 'required|exists:departments,id',
            'language_id' => 'required|exists:languages,id',
            'age_range' => 'required|string',
            'gender' => 'nullable|integer',
            'thumb_image' => 'nullable|string|max:500',
            'cover_image' => 'nullable|string|max:500',
            'submit_date_from' => 'required|date',
            'submit_date_to' => 'nullable|date|after_or_equal:submit_date_from',
            'consideration_date_from' => 'nullable|date|after_or_equal:submit_date_from',
            'consideration_date_to' => 'nullable|date|after_or_equal:consideration_date_from',
            'plan_date_from' => 'nullable|date',
            'plan_date_to' => 'nullable|date|after_or_equal:plan_date_from',
            'current_state' => 'required|string|max:50',
            'judge_number' => 'required|integer|min:1',
            'minimum_score' => 'required|integer|min:0',
            'evaluation_id' => 'nullable|exists:evaluations,id',
            'is_archive' => 'nullable|integer',
            'status' => 'required|boolean',
            'created_by' => 'required|exists:users,id',
            'updated_by' => 'nullable|exists:users,id',
        ];
    }
}
