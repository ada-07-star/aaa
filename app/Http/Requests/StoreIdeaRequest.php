<?php

namespace App\Http\Requests;

use App\Enums\CurrentStateEnum;
use App\Enums\ParticipationTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIdeaRequest extends FormRequest
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
            'topic_id' => 'required|integer|exists:topics,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'final_score' => 'required|integer',
            'is_published' => 'boolean',
            'current_state' => [
                'required',
                Rule::in(CurrentStateEnum::DRAFT),
            ],
            'participation_type' => [
                'required',
                Rule::in(ParticipationTypeEnum::INDIVIDUAL),
            ],
            'users' => 'nullable|array',
            'users.*' => 'integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'topic_id.required' => 'انتخاب موضوع الزامی است.',
            'title.required' => 'عنوان ایده الزامی است.',
            'description.required' => 'توضیحات ایده الزامی است.',
            'participation_type.required' => 'نوع مشارکت الزامی است.',
        ];
    }
}
