<?php

namespace App\Filament\Widgets;

use App\Models\Aluno;
use App\Models\Catequista;
use App\Models\Etapa;
use App\Models\Missa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumoGeralWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Alunos', Aluno::count())
            ->description('Total de alunos cadastrados')
            ->descriptionIcon('heroicon-m-academic-cap')
            ->color('primary')
            ->url(route('filament.admin.resources.alunos.index')),

            Stat::make('Catequistas', Catequista::count())
            ->description('Total de catequistas cadastrados')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('warning')
            ->url(route('filament.admin.resources.catequistas.index')),

            Stat::make('Etapas', Etapa::count())
            ->description('Total de etapas cadastradas')
            ->descriptionIcon('heroicon-m-bookmark')
            ->color('info')
            ->url(route('filament.admin.resources.etapas.index')),

            Stat::make('Missas', Missa::count())
            ->description('Total de missas cadastradas')
            ->descriptionIcon('heroicon-m-building-library')
            ->color('success')
            ->url(route('filament.admin.resources.missas.index')),
        ];
    }
}