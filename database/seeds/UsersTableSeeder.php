<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create(
            [
                'nombre' => 'Administrador',
                'usuario' => 'admin',
                'password' => bcrypt('123456'),
                'perfil_id' => 1,
                'ruta_inicio' => 'usuarios'
            ]
        );
    }
}
