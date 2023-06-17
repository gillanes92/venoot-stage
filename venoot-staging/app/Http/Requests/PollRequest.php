<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class PollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit-actor', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'sometimes|string',
            'questions' => 'required|array',
            'questions.*.question' => 'required|string|max:190',
            'questions.*.type' => 'required|integer',
            'questions.*.alternatives' => 'required_unless:questions.*.type,2|array',
            'questions.*.alternatives.*.alternative' => 'required|string|max:190',
        ];
    }
}
