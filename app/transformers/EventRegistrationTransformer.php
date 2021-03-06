<?php

class EventRegistrationTransformer extends League\Fractal\TransformerAbstract
{

    public function transform(EventRegistration $registration)
    {
        return array(
            'id' => (int)$registration->id,
            'user_id' => (int)$registration->user_id,
            'name' => "{$registration->user->first_name} {$registration->user->last_name}",
            'avatar_thumb' => Request::root() . $registration->user->avatar->url('thumb'),
            'avatar_medium' => Request::root() . $registration->user->avatar->url('medium'),
            'avatar' => Request::root() . $registration->user->avatar->url(),
            'event_id' => (int)$registration->event_id,
            'chair_status' => (bool)$registration->chair_status,
            'driver_status' => (bool)$registration->driver_status,
            'photographer_status' => (bool)$registration->photographer_status,
            'writer_status' => (bool)$registration->writer_status,
            'passengers' => $registration->passengers,
            'created_at' => $registration->created_at->toISO8601String(),
            'updated_at' => $registration->updated_at->toISO8601String()
        );
    }

}
