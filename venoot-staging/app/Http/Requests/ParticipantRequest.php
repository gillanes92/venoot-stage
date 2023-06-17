<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create participant') || Gate::allows('edit-participant', $this);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $participant_id = $this->participant ? $this->participant->id : 0;

        return [
            'data' => 'required',
            'data.name' => 'required',
            'data.lastname' => 'required',
            'data.email' => [
                                'required',
                                Rule::unique('participants', 'data->email')->where(function ($query) {
                                    return $query->where('database_id', $this->database->id);
                                })->ignore($participant_id)
                            ],
            'data.name' => 'required',
        ];
    }
}
