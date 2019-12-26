<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PerfilesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(LugaresTableSeeder::class);
        $this->call(CategoriasProductoTableSeeder::class);
        $this->call(ProductosInventarioTableSeeder::class);
    }
}
