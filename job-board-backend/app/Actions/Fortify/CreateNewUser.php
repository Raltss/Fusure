<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        // Base validation rules
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:job_seeker,employer'],
        ];

        // Add company validation if role is employer
        if ($input['role'] === 'employer') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['company_description'] = ['nullable', 'string'];
            $rules['company_website'] = ['nullable', 'url'];
            $rules['company_location'] = ['required', 'string', 'max:255'];
        }

        Validator::make($input, $rules)->validate();

        // Create user
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => $input['role'],
        ]);

        // Create company if employer
        if ($input['role'] === 'employer') {
            Company::create([
                'user_id' => $user->id,
                'name' => $input['company_name'],
                'description' => $input['company_description'] ?? null,
                'website' => $input['company_website'] ?? null,
                'location' => $input['company_location'],
            ]);
        }

        return $user;
    }
}