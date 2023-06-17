<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $user = User::find($this->user());
        return Gate::allows('update-user', $this->user());
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:30',
            'lastname' => 'required|string|max:30',
            'password' => 'string|min:8|confirmed',
        ];
    }
}
