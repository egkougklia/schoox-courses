<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CourseStatus;
use App\Rules\XSSValidation;

class UpdateCourseRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', new XSSValidation],
            'description' => ['required', 'string', new XSSValidation],
            'status' => ['string', Rule::enum(CourseStatus::class)],
            'is_premium' => 'boolean',
            'tags' => 'nullable|array',
            'tags.*' => ['required', 'string', new XSSValidation]
        ];
    }
}
