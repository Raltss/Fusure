<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobPostingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only employers can create job postings
        return $this->user() && $this->user()->role === 'employer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:full-time,part-time,contract,internship'],
            'location' => ['required', 'string', 'max:255'],
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
            'title.required' => 'Job title is required.',
            'description.required' => 'Job description is required.',
            'type.in' => 'Job type must be full-time, part-time, contract, or internship.',
            'deadline.after' => 'Deadline must be a future date.',
        ];
    }
}