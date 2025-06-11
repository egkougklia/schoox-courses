<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CourseStatus;
use App\Rules\XSSValidation;

class PatchCourseRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255', new XSSValidation],
            'description' => ['sometimes', 'string', new XSSValidation],
            'status' => ['sometimes', 'string', Rule::enum(CourseStatus::class)],
            'is_premium' => 'sometimes|boolean',
            'tags' => 'sometimes|array',
            'tags.*' => ['required', 'string', new XSSValidation]
        ];
    }
}
