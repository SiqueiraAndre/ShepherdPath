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
        // Fim de semana atual (Sábado e Domingo), fixando a segunda como início da semana base local
        $inicioFimDeSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(5); // Sábado (00:00)
        $fimFimDeSemana = Carbon::now()->startOfWeek(Carbon::MONDAY)->addDays(6)->endOfDay(); // Domingo (23:59)

        $totalPresencas = Presenca::whereBetween('data_missa', [$inicioFimDeSemana, $fimFimDeSemana])->count();

        return [
            Stat::make('Presenças no Fim de Semana', $totalPresencas)
                ->description('Missa de Sábado e Domingo')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
