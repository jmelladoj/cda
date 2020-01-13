<?php

use App\Perfil;
use Illuminate\Database\Seeder;

class PerfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Perfil::create([
            'nombre' => 'Administrador',
            'menu_perfiles' => 1,
            'menu_usuarios' => 1,
            'menu_ordenes_compra' => 1,
            'menu_lugares' => 1,
            'menu_inventario' => 1,
            'menu_proveedores' => 1,
            'menu_categorias_productos' => 1
        ]);
    }
}
