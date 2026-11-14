<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('use-comments') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'post_id' => ['required', 'integer', Rule::exists('posts', 'id')->where('published', 'published')],
        'comment' => ['required', 'string', 'max:255'],
        ];
    }

      public function messages(): array
    {
        return [
            'comment.required'=>'Type something in comment box',
        ];
    }
}
