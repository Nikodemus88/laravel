<?php

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/events.json");
        $data = json_decode($json);
        foreach($data as $obj) {
            Event::create(array(
                'id' => $obj->id,
                'user_id' => $obj->user_id,
                'title' => $obj->title,
                'description' => $obj->description,
                'duration' => $obj->duration,
                'start' => $obj->start,
                'allDay' => $obj->allDay,
                'rrule' => $obj->rrule,
                'price' => $obj->price,
                'project_id' => $obj->project_id
            ));
            $this->command->info('Event: (project #' . $obj->project_id . ') ' . $obj->title . ' added');
        }
    }
}
