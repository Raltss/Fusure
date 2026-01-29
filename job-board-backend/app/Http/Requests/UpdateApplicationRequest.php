<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only employers can update application status
        // And only if the job posting belongs to their company
        $application = $this->route('application');

        return $this->user() &&
               $this->user()->role === 'employer' &&
               $application->jobPosting->company->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,reviewed,shortlisted,interviewed,rejected,accepted'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Application status is required.',
            'status.in' => 'Invalid application status.',
            'notes.max' => 'Notes are too long (max 1000 characters).',
        ];
    }
}
