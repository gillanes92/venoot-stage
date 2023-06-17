<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('edit-product', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_publish' => 'required|integer|min:0',
            'event_landing' => 'required|integer|min:0',
            'poll_publish' => 'required|integer|min:0',
            'mailings' => 'required|integer|min:0',
            'register_keys' => 'required|integer|min:0',
            'register_days' => 'required|integer|min:0',
        ];
    }
}
