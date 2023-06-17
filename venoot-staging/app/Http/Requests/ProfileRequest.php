<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create database') || Gate::allows('edit-database', $this->database);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:180',
            'conditions' =>  'sometimes|array',
            'conditions.*.variable' => 'required|string|max:180',
            'conditions.*.operation' => 'required|string|in:=,<,<=,>,>=,like',
            'conditions.*.parameter' => 'required|string|max:180',
        ];
    }
}
