<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $json = File::get("database/data/users.json");
        $data = json_decode($json);
        foreach($data as $obj) {
            User::create(array(
                'name' => $obj->name,
                'email' => $obj->email,
                'password' => bcrypt($obj->password)
            ));
            $this->command->info('User: ' . $obj->name . ' added');
        }
    }
}
