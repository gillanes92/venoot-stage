<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $discount_id = $this->discount ? $this->discount->id : 0;

        return [
            'name' => 'required|string|max:50',
            'trigger' => 'required|string|in:none,cupon,key,quantity',
            'trigger_value' => [
                'required_if:type,cupon|required_if:type,key|string|max:16',
                Rule::unique('discounts', 'trigger_value')->where('company_id', Auth::user()->company->id)->ignore($discount_id)
            ],

            'trigger_quantity' => 'required_if:type,quantity|nullable|integer|min:0',

            'type' => 'required|string|in:percentual',
            'type_quantity' => 'required|integer|min:0',

            'tickets' => 'array',
            'tickets.*.id' => 'required|integer|exists:tickets,id',

            'quota' => 'sometimes|nullable|integer|min:0',

        ];
    }
}
