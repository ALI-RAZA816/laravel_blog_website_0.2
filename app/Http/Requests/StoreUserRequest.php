<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string|max:50',
            'emailaddress'=>'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', 'max:72', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Name is required',
            'name.string'=>'Name must be string',
            'name.max'=>'Name must not exceed 50 characters',
            'emailaddress.required'=>'Email is required',
            'emailaddress.email'=>'Email must be valid',
            'emailaddress.unique'=>'Email already exists',
            'password.required'=>'Password is required',
            'password.min'=>'Password must be at least 8 characters',
            'password.confirmed'=>'Password does not match',
        ];
    }
}
