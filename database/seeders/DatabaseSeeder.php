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


        DB::table('permissions')
            ->insert([
                ['value' => 1],
                ['value' => 5],
                ['value' => 9],
            ]);

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


        DB::table('profile_permission')
            ->insert([
                ['profile_id'   => 1, 'permission_id'  => 3],
            ]);


        $c = Company::create([
            'cpf_cnpj'      => '11.111.111/0001-11',
            'name'          => 'Empresa',
            'description'   => 'Descrição da empresa'
        ]);

        User::create([
            'name'          => 'admin',
            'email'         => 'admin@email.com',
            'password'      => Hash::make('password'),
            'company_id'    => $c->id,
            'profile_id'    => 1
        ]);
    }
}
