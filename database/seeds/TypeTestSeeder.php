<?php

use Illuminate\Database\Seeder;

class TypeTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('type_tests')->insert([
            'description' => "Verdadero/Falso",
        ]);
        DB::table('type_tests')->insert([
            'description' => "Opcion Multiple",
        ]);
    }
}
