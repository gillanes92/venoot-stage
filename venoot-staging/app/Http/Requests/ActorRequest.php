<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ActorRequest extends FormRequest
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
            'prefix' => 'nullable|string|max:120',
            'name' => 'required|string|max:120',
            'lastname' => 'required|string|max:120',
            'email' => 'required|email',
            'category' => 'sometimes|string|max:120',
            'job' => 'sometimes|string|max:120',
            'organization' => 'sometimes|string|max:120',
            'description' => 'sometimes|string',
            'photo' => 'required|string|max:120',
            'links' => 'sometimes|array',
            'links.*.name' => 'required|string|max:120',
            'links.*.http' => 'required|string|max:180',
            'email' => 'sometimes|email',
        ];
    }
}
