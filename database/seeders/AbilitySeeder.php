<?php

namespace Database\Seeders;

use App\Models\Ability;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = [
           [
                'name' => 'Listar usuários',
                'slug' => 'user_list',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Seleciona usuários',
                'slug' => 'user_by_id',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar usuário',
                'slug' => 'user_insert',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),

            ],
            [
                'name' => 'Editar usuário',
                'slug' => 'user_edit',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar usuário',
                'slug' => 'user_delete',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Trocar de senha',
                'slug' => 'user_change_password',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Resetar senha usuário',
                'slug' => 'user_reset_senha',
                'functionality' => 'usuario',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],


            /*
            |--------------------------------------------------------------------------
            | Abilities for profile
            |--------------------------------------------------------------------------
            */
            [
                'name' => 'Listar perfis',
                'slug' => 'profile_list',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Listar habilidade por perfl',
                'slug' => 'profile_list_ability',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Cadastrar perfil',
                'slug' => 'profile_insert',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Editar perfil',
                'slug' => 'profile_edit',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Seleciona perfil',
                'slug' => 'profile_by_id',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Deletar perfil',
                'slug' => 'profile_delete',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Adicionar Permissões ao Perfil',
                'slug' => 'profile_add_permisao',
                'functionality' => 'perfil',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Listar typeofperson',
                'slug' => 'typeofperson_list',
                'functionality' => 'tipo de pessoa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar typeofperson',
                'slug' => 'typeofperson_insert',
                'functionality' => 'tipo de pessoa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar typeofperson',
                'slug' => 'typeofperson_by_id',
                'functionality' => 'tipo de pessoa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar typeofperson',
                'slug' => 'typeofperson_edit',
                'functionality' => 'tipo de pessoa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar typeofperson',
                'slug' => 'typeofperson_delete',
                'functionality' => 'tipo de pessoa',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],



            [
                'name' => 'Listar establishment',
                'slug' => 'establishment_list',
                'functionality' => 'estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishment',
                'slug' => 'establishment_insert',
                'functionality' => 'estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishment',
                'slug' => 'establishment_by_id',
                'functionality' => 'estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishment',
                'slug' => 'establishment_edit',
                'functionality' => 'estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishment',
                'slug' => 'establishment_delete',
                'functionality' => 'estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar services',
                'slug' => 'services_list',
                'functionality' => 'serviços',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar services',
                'slug' => 'services_insert',
                'functionality' => 'serviços',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar services',
                'slug' => 'services_by_id',
                'functionality' => 'serviços',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar services',
                'slug' => 'services_edit',
                'functionality' => 'serviços',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar services',
                'slug' => 'services_delete',
                'functionality' => 'serviços',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentservices',
                'slug' => 'establishmentservices_list',
                'functionality' => 'serviços do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentservices',
                'slug' => 'establishmentservices_insert',
                'functionality' => 'serviços do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentservices',
                'slug' => 'establishmentservices_by_id',
                'functionality' => 'serviços do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentservices',
                'slug' => 'establishmentservices_edit',
                'functionality' => 'serviços do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentservices',
                'slug' => 'establishmentservices_delete',
                'functionality' => 'serviços do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar status',
                'slug' => 'status_list',
                'functionality' => 'status',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar status',
                'slug' => 'status_insert',
                'functionality' => 'status',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar status',
                'slug' => 'status_by_id',
                'functionality' => 'status',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar status',
                'slug' => 'status_edit',
                'functionality' => 'status',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar status',
                'slug' => 'status_delete',
                'functionality' => 'status',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Listar client',
                'slug' => 'client_list',
                'functionality' => 'cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar client',
                'slug' => 'client_insert',
                'functionality' => 'cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar client',
                'slug' => 'client_by_id',
                'functionality' => 'cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar client',
                'slug' => 'client_edit',
                'functionality' => 'cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar client',
                'slug' => 'client_delete',
                'functionality' => 'cliente',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar professional',
                'slug' => 'professional_list',
                'functionality' => 'profissional',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar professional',
                'slug' => 'professional_insert',
                'functionality' => 'profissional',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar professional',
                'slug' => 'professional_by_id',
                'functionality' => 'profissional',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar professional',
                'slug' => 'professional_edit',
                'functionality' => 'profissional',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar professional',
                'slug' => 'professional_delete',
                'functionality' => 'profissional',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentprofessional',
                'slug' => 'establishmentprofessional_list',
                'functionality' => 'profissional do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentprofessional',
                'slug' => 'establishmentprofessional_insert',
                'functionality' => 'profissional do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentprofessional',
                'slug' => 'establishmentprofessional_by_id',
                'functionality' => 'profissional do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentprofessional',
                'slug' => 'establishmentprofessional_edit',
                'functionality' => 'profissional do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar establishmentprofessional',
                'slug' => 'establishmentprofessional_delete',
                'functionality' => 'profissional do estabelecimento',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar list',
                'slug' => 'list_list',
                'functionality' => 'lista',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar list',
                'slug' => 'list_insert',
                'functionality' => 'lista',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar list',
                'slug' => 'list_by_id',
                'functionality' => 'lista',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar list',
                'slug' => 'list_edit',
                'functionality' => 'lista',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],


            [
                'name' => 'Listar list',
                'slug' => 'list_delete',
                'functionality' => 'lista',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        foreach ($abilities as $key => $value) {
            Ability::firstOrCreate([
                'name' => $value['name'],
                'slug' => $value['slug'],
                'functionality' => $value['functionality'],
            ]);
        }

    }
}
