<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cria os perfis de acesso da aplicação
        $roles = [
            'super_admin',   // acesso irrestrito (Filament Shield bypass)
            'administrador',
            'supervisor',
            'coordenador',
            'catequista',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Gera/atualiza todas as permissões dos resources no banco
        Artisan::call('shield:generate', ['--all' => true, '--minimal' => true]);

        // Atribui todas as permissões geradas ao role 'administrador'
        $adminRole = Role::findByName('administrador', 'web');
        $adminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());

        // Cria o usuário admin padrão caso não exista
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin@catequese'),
            ]
        );

        // Atribui super_admin para bypass irrestrito no Filament Shield
        $admin->syncRoles(['super_admin']);

        $this->command->info('✅ Roles criados: ' . implode(', ', $roles));
        $this->command->info('✅ Permissões geradas e atribuídas ao role administrador.');
        $this->command->info('✅ Usuário admin: admin@admin.com (senha: admin@catequese) → role: super_admin');
    }
}