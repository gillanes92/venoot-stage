<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Update request
        if ($this->id) {
            return Gate::allows('update-company', $this);

        // Store request
        } else {
            return Gate::allows('store-company');
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'social_reason' => 'required|string|max:30',
            'area' => 'required|string|max:30',
            'rut' => 'required|string|max:10',
            'address' => 'required|string|max:80',
            'city' => 'required|string|max:20',
            'commune_id' => 'required|integer',
            'region_id' => 'required|integer',
            'phone' => 'required|string|max:20',
            'logo' => 'required|string|max:200',
            'payment_type' => 'required|integer|in:0,1,2,3',
        ];
    }
}
