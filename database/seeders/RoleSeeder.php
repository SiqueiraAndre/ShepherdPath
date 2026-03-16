<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Cria os 4 perfis de acesso
        $roles = [
            'administrador',
            'supervisor',
            'coordenador',
            'catequista',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // Cria o usuário admin padrão caso não exista
        $admin = User::firstOrCreate(
            ['email' => 'admin@catequese.local'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('admin@catequese'),
            ]
        );

        // Garante que o admin tenha o role administrador
        $admin->syncRoles(['administrador']);

        $this->command->info('✅ Roles criados: ' . implode(', ', $roles));
        $this->command->info('✅ Usuário admin criado: admin@catequese.local (senha: admin@catequese)');
    }
}
