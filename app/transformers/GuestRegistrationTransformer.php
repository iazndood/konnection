<?php

class GuestRegistrationTransformer extends League\Fractal\TransformerAbstract
{

    public function transform(GuestRegistration $registration)
    {
        return array(
            'id' => (int)$registration->id,
            'name' => "{$registration->first_name} {$registration->last_name}",
            'event_id' => (int)$registration->event_id,
            'driver_status' => (bool)$registration->driver_status,
            'passengers' => $registration->passengers,
            'created_at' => $registration->created_at->toISO8601String(),
            'updated_at' => $registration->updated_at->toISO8601String()
        );
    }

}
