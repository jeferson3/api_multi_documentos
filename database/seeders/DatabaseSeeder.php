<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();


        foreach (range(1, 9) as $item) {
            DB::table('permissions')
                ->insert(['value' => $item]);
        }

        DB::table('profiles')
            ->insert([
                [
                    'name'        => 'Administrador geral',
                    'description' => 'Administrador poderá ver todas as informações de todas as empresas',
                ],
                [
                    'name'        => 'Gerente',
                    'description' => 'Poderá interagir apenas com as informações da sua empresa',
                ],
                [
                    'name'        => 'Usuário do sistema',
                    'description' => 'Poderá interagir apenas com seus dados',
                ]
            ]);


        DB::table('profile_permission')
            ->insert([
                ['profile_id'   => 1, 'permission_id'  => 9],
            ]);


        User::create([
            'name'          => 'admin',
            'email'         => 'admin@email.com',
            'password'      => Hash::make('password'),
            'profile_id'    => 1
        ]);

    }
}
