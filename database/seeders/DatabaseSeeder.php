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

        DB::table('profiles')
            ->insert([
                [
                    'name'        => 'Administrador geral',
                    'description' => 'Administrador poderá ver todas as informações de todas as empresas',
                ],
                [
                    'name'        => 'Gerente',
                    'description' => 'poderá interagir apenas com as informações da sua empresa',
                ],
                [
                    'name'        => 'Usuário do sistema',
                    'description' => 'poderá interagir apenas com seus dados',
                ]
            ]);

        $c = Company::create([
            'name'          => 'c1',
            'description'   => 'description'
        ]);

        User::create([
            'name'          => 'admin',
            'email'         => 'admin@email.com',
            'password'      => Hash::make('password'),
            'company_id'    => $c->id
        ]);
    }
}
