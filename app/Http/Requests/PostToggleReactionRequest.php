<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostToggleReactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'like' => filter_var($this->input('like'), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules()
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'like'    => ['required', 'boolean'], 
        ];
    } 
    
    # Get custom error messages for validation.
    public function messages()
    {
        return [
            'post_id.required' => 'Post ID is required to toggle the reaction.',
            'post_id.integer'  => 'Post ID must be a valid integer.',
            'post_id.exists'   => 'The selected post does not exist.',
            'like.required'    => 'You must provide a reaction (like or unlike).',
            'like.boolean'     => 'The reaction must be either true or false.',
        ];
    }
}
