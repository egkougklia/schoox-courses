<?php

namespace App\Http\Requests\Courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\CourseStatus;
use App\Rules\XSSValidation;

class IndexCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // turn is_premium into boolean
        if ($this->has('is_premium')) {
            $this->merge([
                'is_premium' => filter_var($this->input('is_premium'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'is_premium' => 'sometimes|boolean',
            'status' => ['sometimes', 'string', Rule::enum(CourseStatus::class)],
            'tag' => ['sometimes', 'string', new XSSValidation]
        ];
    }
}
