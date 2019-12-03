<?php

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->delete();
        $json = File::get("database/data/companies.json");
        $data = json_decode($json);
        foreach($data as $obj) {
            Company::create(array(
                'name' => $obj->name
            ));
            $this->command->info('Company: ' . $obj->name . ' added');
        }
    }
}
