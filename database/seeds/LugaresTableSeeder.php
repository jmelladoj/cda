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
        Lugar::create(['nombre' => 'Cuarto frío' ]);
        Lugar::create(['nombre' => 'Cuarto caliente' ]);
        Lugar::create(['nombre' => 'Personal' ]);
        Lugar::create(['nombre' => 'Pasteleria' ]);
        Lugar::create(['nombre' => 'Mantención' ]);
        Lugar::create(['nombre' => 'Spa y cafetería' ]);
        Lugar::create(['nombre' => 'Centro de eventos' ]);
        Lugar::create(['nombre' => 'Salon y barra' ]);
    }
}
