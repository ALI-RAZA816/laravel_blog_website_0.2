<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-categories') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cat_name' => 'required|string|max:100|unique:categories,name',
            'slug'=>['required','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/','unique:categories,slug'],
            'description'=>'nullable|max:100',
            'icon_name'=>'required'
        ];
    }

    public function messages(): array
    {
        return [
            'cat_name.required'=> 'Category name required',
            'slug.required'=> 'Slug is required',
            'slug.regex'=> 'Slug must be lowercase with hyphens only (e.g. my-category)',
            'description.max'=> 'Description must not exceed 100 characters',
            'icon_name.required'=> 'Icon is required',
        ];
    }

}
