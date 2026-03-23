<?php

namespace App\Filament\Widgets;

use App\Models\Catequizando;
use App\Models\Catequista;
use App\Models\Etapa;
use App\Models\LinkAcesso;
use App\Models\Missa;
use App\Models\Presenca;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ResumoGeralWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Lógica de Presenças (Fim de semana atual)
        $inicioFimDeSemana = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $fimFimDeSemana = Carbon::now()->startOfWeek(Carbon::SATURDAY)->addDays(6)->endOfDay();
        $totalPresencas = Presenca::whereBetween('data_missa', [$inicioFimDeSemana, $fimFimDeSemana])->count();

        return [
            Stat::make('Catequizandos', Catequizando::count())
            ->description('Total de Catequizandos')
            ->descriptionIcon('heroicon-m-academic-cap')
            ->color('primary')
            ->url(route('filament.admin.resources.catequizandos.index')),

            Stat::make('Catequistas', Catequista::count())
            ->description('Total de Catequistas')
            ->descriptionIcon('heroicon-m-user-group')
            ->color('warning')
            ->url(route('filament.admin.resources.catequistas.index')),

            Stat::make('Etapas', Etapa::count())
            ->description('Total de Etapas')
            ->descriptionIcon('heroicon-m-bookmark')
            ->color('info')
            ->url(route('filament.admin.resources.etapas.index')),

            Stat::make('Links de Presença', LinkAcesso::where('is_ativo', true)->count())
            ->description('Total de Links Ativos')
            ->descriptionIcon('heroicon-o-link')
            ->color('success')
            ->url(route('filament.admin.resources.link-acessos.index')),

            Stat::make('Presenças Confirmadas', $totalPresencas)
            ->description('Missa de Sábado e Domingo')
            ->descriptionIcon('heroicon-m-users')
            ->color('danger')
            ->url(route('filament.admin.resources.presencas.index')),

            // Stat::make('Missas', Missa::count())
            // ->description('Total de missas cadastradas')
            // ->descriptionIcon('heroicon-m-building-library')
            // ->color('success')
            // ->url(route('filament.admin.resources.missas.index')),
        ];
    }
}