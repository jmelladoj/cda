<?php

use App\ProductoInventario;
use Illuminate\Database\Seeder;

class ProductosInventarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        ProductoInventario::create([
            'nombre' => 'Carnicero',
            'unidad' => 'KG',
            'stock' => 20,
            'stock_critico' => 25,
            'valor_actual' => 4000,
            'valor_ultimo' => 3800,
            'categoria_productos_id' => 1
        ]);

        ProductoInventario::create([
            'nombre' => 'Queso mantecoso',
            'unidad' => 'KG',
            'stock' => 10,
            'stock_critico' => 8,
            'valor_actual' => 4200,
            'valor_ultimo' => 1400,
            'categoria_productos_id' => 2
        ]);

        ProductoInventario::create([
            'nombre' => 'Cerveza totem',
            'unidad' => 'UNI',
            'stock' => 20,
            'stock_critico' => 25,
            'valor_actual' => 3000,
            'valor_ultimo' => 3200,
            'categoria_productos_id' => 3
        ]);

        ProductoInventario::create([
            'nombre' => 'Abastero',
            'unidad' => 'KG',
            'stock' => 50,
            'stock_critico' => 20,
            'valor_actual' => 3000,
            'valor_ultimo' => 2800,
            'categoria_productos_id' => 1
        ]);

        ProductoInventario::create([
            'nombre' => 'Queso chanco',
            'unidad' => 'KG',
            'stock' => 50,
            'stock_critico' => 52,
            'valor_actual' => 3000,
            'valor_ultimo' => 3200,
            'categoria_productos_id' => 2
        ]);
    }
}
