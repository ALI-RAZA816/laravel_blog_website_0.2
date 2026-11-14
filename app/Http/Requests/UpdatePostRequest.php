<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('write-posts') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string|max:255',
            'description'=>'required|string',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'category'=> 'required|exists:categories,id',
            'tags'=>'required|string',
            'published' => 'required|in:published,draft',
            'description' => 'required|string|max:200000',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Title is required.',
            'description.required' => 'Description is required.',
            'image.image'          => 'The file must be an image.',
            'image.mimes'          => 'File type must be jpg, jpeg, or png.',
            'image.max'            => 'Image size must not be greater than 3MB.',
            'category.required'    => 'Category is required.',
            'category.exists'      => 'Selected category does not exist.',
            'tags.required'        => 'At least one tag is required.',
        ];
    }
}
