<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Nucleo;
use Illuminate\Database\Seeder;

class NucleosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rectorado = Nucleo::create([
            'codigo_1'              => 1,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 11,
            'nombre'                => 'RECTORADO',
            'direccion'             => ''
        ]);

        $sucre = Nucleo::create([
            'codigo_1'              => 2,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 21,
            'nombre'                => 'NUCLEO DE SUCRE',
            'direccion'             => ''
        ]);

        $anzoategui = Nucleo::create([
            'codigo_1'              => 3,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 31,
            'nombre'                => 'NUCLEO DE ANZOATEGUI',
            'direccion'             => ''
        ]);

        $monagas = Nucleo::create([
            'codigo_1'              => 4,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 41,
            'nombre'                => 'NUCLEO DE MONAGAS',
            'direccion'             => ''
        ]);

        $bolivar = Nucleo::create([
            'codigo_1'              => 5,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 51,
            'nombre'                => 'NUCLEO DE BOLIVAR',
            'direccion'             => ''
        ]);

        $nueva_esparta = Nucleo::create([
            'codigo_1'              => 6,
            'codigo_2'              => 1,
            'codigo_concatenado'    => 61,
            'nombre'                => 'NUCLEO NUEVA ESPARTA',
            'direccion'             => ''
        ]);
    }
}
