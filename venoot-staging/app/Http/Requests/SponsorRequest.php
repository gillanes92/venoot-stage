<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create sponsor') || Gate::allows('edit-sponsor', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo' => 'required|string',
            'name' => 'sometimes|string|max:30',
            'description' => 'sometimes|string|max:180',
            'category' => 'required|string|max:50',
            'url' => 'sometimes|nullable|url',
        ];
    }
}
