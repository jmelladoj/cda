<?php

use App\Lugar;
use Illuminate\Database\Seeder;

class LugaresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Lugar::create([
            'nombre' => 'Restaurant'
        ]);

        Lugar::create([
            'nombre' => 'Eventos'
        ]);

        Lugar::create([
            'nombre' => 'Barra'
        ]);
    }
}
