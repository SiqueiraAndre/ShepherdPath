<?php

namespace App\Filament\Widgets;

use App\Models\Presenca;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PresencasWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Fim de semana atual ou anterior se estivermos no meio da semana
        $inicioFimDeSemana = Carbon::now()->startOfWeek()->addDays(5); // Sábado
        $fimFimDeSemana = Carbon::now()->endOfWeek(); // Domingo

        $totalPresencas = Presenca::whereBetween('data_missa', [$inicioFimDeSemana, $fimFimDeSemana])->count();

        return [
            Stat::make('Presenças no Fim de Semana', $totalPresencas)
                ->description('Missa de Sábado e Domingo')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
