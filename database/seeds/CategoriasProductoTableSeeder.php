<?php

use App\CategoriaProducto;
use Illuminate\Database\Seeder;

class CategoriasProductoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        CategoriaProducto::create([
            'nombre' => 'Carnes'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Quesos'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Cervezas'
        ]);
    }
}
