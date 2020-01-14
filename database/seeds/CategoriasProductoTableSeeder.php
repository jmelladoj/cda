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
            'nombre' => 'Abarrote'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Aseo'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Bebestible'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Carnes'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Congelados'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Lacteos'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Otros'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Papeleria'
        ]);

        CategoriaProducto::create([
            'nombre' => 'Pes. y mariscos'
        ]);
    }
}
