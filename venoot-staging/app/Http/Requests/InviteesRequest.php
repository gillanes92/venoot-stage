<?php

namespace App\Http\Requests;

use App\Participation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InviteesRequest extends FormRequest
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
        $participation = $this->uuid ? Participation::where('uuid', $this->uuid)->first() : null;

        return [
            'uuid' => 'required|uuid',
            'emails' => 'required|array|size:'.$participation->event->invitees,
            'emails.*' => [
                'required',
                'distinct',
                'email',
                Rule::unique('participants', 'data->email')->where(function ($query) use ($participation) {
                    return $query->whereIn('id', $participation->event->participants->pluck('id'));
                }),
            ]
        ];
    }

    public function messages() {
        $messages = [];
        foreach($this->request->get('emails') as $key => $val) {
          $messages['emails.'.$key.'.unique'] = 'Correo Invitado #'.($key + 1).' ya se encuentra ocupado.';
          $messages['emails.'.$key.'.email'] = 'Correo Invitado #'.($key + 1).' no tiene formato de correo.';
        }
        return $messages;
      }

    public function attributes()
{
    return [
        'emails.*' => 'correo invitado ',
    ];
}
}
