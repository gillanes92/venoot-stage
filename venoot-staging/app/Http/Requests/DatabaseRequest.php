<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class DatabaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create database') || Gate::allows('edit-database', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:60',
            'fields' => 'required',
            'fields.*.name' => 'required|string|max:500',
            'fields.*.type' => 'required|string|in:string,email,number,boolean,date,datetime,image,choice,pdf',
            'fields.*.required' => 'required|boolean',
            'fields.*.in_form' => 'required|boolean',
        ];
    }
}
