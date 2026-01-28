<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only the company owner (employer) can update their job postings
        $jobPosting = $this->route('job_posting');
        
        return $this->user() && 
               $this->user()->role === 'employer' &&
               $jobPosting->company->user_id === $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'type' => ['sometimes', 'in:full-time,part-time,contract,internship'],
            'location' => ['sometimes', 'string', 'max:255'],
            'salary_min' => ['nullable', 'string', 'max:50'],
            'salary_max' => ['nullable', 'string', 'max:50'],
            'requirements' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:open,closed'],
            'deadline' => ['nullable', 'date', 'after:today'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Job type must be full-time, part-time, contract, or internship.',
            'deadline.after' => 'Deadline must be a future date.',
        ];
    }
}