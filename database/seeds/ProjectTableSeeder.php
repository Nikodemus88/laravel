<?php

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/projects.json");
        $data = json_decode($json);
        foreach($data as $obj) {
            Project::create(array(
                'id' => $obj->id,
                'title' => $obj->title,
                'description' => $obj->description,
                'url' => $obj->url,
                'img_url' => $obj->img_url,
                'user_id' => $obj->user_id,
                'color' => $obj->color
            ));
            $this->command->info('Project: ' . $obj->title . ' added');
        }
    }
}
