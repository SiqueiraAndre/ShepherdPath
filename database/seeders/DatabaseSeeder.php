<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Criando Missas
        $missas = [
            'Sábado - 18h',
            'Domingo - 8h',
            'Domingo - 9h30',
            'Domingo - 11h',
            'Domingo - 19h30',
        ];

        foreach ($missas as $missa) {
            \App\Models\Missa::create(['descricao' => $missa]);
        }

        // Criando Etapas
        $etapa1 = \App\Models\Etapa::create(['nome' => '1ª etapa']);
        $etapa2 = \App\Models\Etapa::create(['nome' => '2ª etapa']);
        $etapa3 = \App\Models\Etapa::create(['nome' => '3ª etapa']);

        // Criando Catequistas da 1ª Etapa
        $etapa1->catequistas()->createMany([
            ['nomes' => 'Eduardo e Vivian'],
            ['nomes' => 'Fernando e Diana'],
        ]);

        // Criando Catequistas da 2ª Etapa
        $etapa2->catequistas()->createMany([
            ['nomes' => 'Nani e Rosana'],
            ['nomes' => 'Cristiane e Patrícia'],
        ]);

        // Criando Catequistas da 3ª Etapa
        $etapa3->catequistas()->createMany([
            ['nomes' => 'Joana e Fabiana'],
            ['nomes' => 'Maria e Clara'],
        ]);
    }
}
