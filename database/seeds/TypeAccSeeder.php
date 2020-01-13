<?php

use Illuminate\Database\Seeder;

class TypeAccSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('type_accs')->insert([
            'description' => "student",

        ]);

        DB::table('type_accs')->insert([
            'description' => "teacher",
        ]);

        DB::table('type_accs')->insert([
            'description' => "admin",
        ]);
    }
}
