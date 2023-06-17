<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class EventRequest extends FormRequest
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
            'category' => 'required|string|in:recitales,entretenimientos,deportes,cursos,corporativoc,corporativoa,ceremonia',
            'banner' => 'required|string',
            'logo_event' => 'required|string',
            'name' => 'required|string|max:200',
            'location' => 'nullable|string|max:60',
            'start_date' => 'nullable|date_format:Y-m-d',
            'start_time' => 'date_format:H:i',
            'end_date' => 'nullable|date_format:Y-m-d',
            'end_time' => 'date_format:H:i',
            'description' => 'string',
            'twitter' => 'sometimes|url|nullable',
            'facebook' => 'sometimes|url|nullable',
            'instagram' => 'sometimes|url|nullable',
            'google_plus' => 'sometimes|url|nullable',
            'web' => 'sometimes|url|nullable',
            'linkedin' => 'sometimes|url|nullable',
            'email' => 'sometimes|email',
            'secure_video' => 'sometimes|nullable|boolean',
            'secure_video_extras' => 'sometimes|nullable|boolean',
            'video_category' => 'sometimes|nullable|string',
            'shared_chat' => 'sometimes|nullable|boolean',

            'quota' => 'sometimes|nullable|integer|min:0',
            'over_quota' => 'sometimes|nullable|boolean',
            'apology' => 'sometimes|nullable|string|max:180',

            'extra_images' => 'sometimes|array',
            'extra_images.*' => 'sometimes|string|max:120',

            'has_confirmation' => 'sometimes|boolean',
            'confirmation_key' => 'required_if:has_confirmation,true|string|nullable',

            'actors' => 'sometimes|array',

            'invitees' => 'required|integer|min:0',
            'profile_id' => 'required|exists:profiles,id',
            'estimate_id' => 'required|exists:estimates,id',

            'activities' =>  'sometimes|array',
            'activities.*.name' => 'required|string|max:180',
            'activities.*.location' => 'required|string|max:180',
            'activities.*.date' => 'required|date_format:Y-m-d',

            'activities.*.actors' => 'sometimes|array',
            'activities.*.actors.*.id' => 'required|exists:actors,id',

            'activities.*.sponsors' => 'sometimes|array',
            'activities.*.sponsors.*.id' => 'required|exists:sponsors,id',

            'producer' => 'sometimes|string|nullable',
            'producer_password' => 'sometimes|string|nullable|min:8',

            'pretty_url' => 'nullable|alpha_dash|unique:events,pretty_url' . ($this->event ? ',' . $this->event->id : ''),

            'webpay_accepted' => 'sometimes|boolean',
            'paypal_accepted' => 'sometimes|boolean'
        ];
    }
}
