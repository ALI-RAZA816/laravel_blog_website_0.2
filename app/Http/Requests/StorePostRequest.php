<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:200000',
            'image'       => 'required|image|mimes:jpg,jpeg,png|max:3072',
            'category'    => 'required|exists:categories,id',
            'tags'        => 'required|string',
            'published'   => 'required|in:published,draft',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $tags = json_decode((string) $this->tags, true);
            if (!is_array($tags) || count($tags) < 1) {
                $v->errors()->add('tags', 'At least one tag is required.');
            } elseif (count($tags) > 5) {
                $v->errors()->add('tags', 'Maximum 5 tags allowed.');
            } elseif (collect($tags)->contains(fn ($t) => !is_string($t) || mb_strlen($t) > 30)) {
                $v->errors()->add('tags', 'Each tag must be text up to 30 characters.');
            }
        });
    }
}
