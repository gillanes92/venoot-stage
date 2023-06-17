<?php

use App\Participation;
use App\Database;
use App\Event;

use Carbon\Carbon;

use Illuminate\Database\Seeder;

class ParticipantSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        $database = Database::first();
        $event = Event::find(2);

        $database->participants()->delete();

        foreach (range(1, 200) as $index) {

            $data = [
                'name' => $faker->firstName,
                'lastname' => $faker->lastname,
                'email' => $faker->email
            ];

            $participant = $database->participants()->create([
                'data' => $data
            ]);

            $event_date = Carbon::parse($event->start_date);

            $confirmed_sent_at = rand(1, 10) > 3 ? $event_date->subDays(rand(10, 15)) : null;
            $confirmed_at = null;
            $confirmed_status = null;
            $registered_at = null;

            if ($confirmed_sent_at) {
                $confirmed_at = rand(1, 10) > 5 ? $confirmed_sent_at->addDays(rand(1, 6)) : null;

                if ($confirmed_at) {
                    $confirmed_status = rand(1, 10) > 3 ? true : false;

                    if ($confirmed_status) {
                        $registered_at = rand(1, 10) > 2 ? $event_date : null;
                    }
                }
            }

            Participation::create([
                'participant_id' => $participant->id,
                'event_id' => $event->id,
                'uuid' => (string) Str::uuid(),
                'confirmed_status' => $confirmed_status ?? null,
                'confirmed_sent_at' => $confirmed_sent_at ?? null,
                'confirmed_at' => $confirmed_at ?? null,
                'registered_at' => $registered_at ?? null
            ]);
        }
    }
}
