<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EstimateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create event') || Gate::allows('edit-event', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'landing' => 'required|boolean',
            'confirmation_form' => 'required|boolean',
            'mailings' => 'required|boolean',
            'mailings_quantity' => 'required_if:mailings,true|integer|min:0|max:500000',
            'polls' => 'required|boolean',
            'polls_quantity' => 'required_if:polls,true|integer|min:0|max:100',
            'register_keys' => 'required|boolean',
            'register_keys_quantity' => 'required_if:register_keys,true|integer|min:0|max:50',
            'ticket_sale' => 'required|boolean',
            'free_tickets' => 'required|boolean',
        ];
    }
}
