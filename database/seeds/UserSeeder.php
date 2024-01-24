<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::Create([
            'usuario'       => 'admin',
            'password'      => '123456',
            'email'         => 'administrador@test.com',
            'status'        => '1',
            'personal_id'   => '1',
        ]);
        $jefe = User::Create([
            'usuario'       => 'ptata',
            'password'      => '123456',
            'email'         => 'ptata@test.com',
            'status'        => '1',
            'personal_id'   => '2',
        ]);
        // $standar = User::Create([
        //     'name'      => 'Jose Alvino',
        //     'apellido'  => 'User',
        //     'username'  => 'estandar',
        //     'password'  => '123456',
        //     'email'     => 'estandar@test.com',
        // ]);

        $admin->assignRole('administrador');
        $jefe->assignRole('jefe');
        // $standar->assignRole('secretario');
    }
}
