<?php

namespace App\Filament\Widgets;

use App\Models\Presenca;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PresencasWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '30s';

    public function getColumnSpan(): int|string|array
    {
        return 4;
    }

    protected function getStats(): array
    {
        return [];
    // // Fim de semana atual (Sábado e Domingo), fixando a Sábado como início da semana base local
    // $inicioFimDeSemana = Carbon::now()->startOfWeek(Carbon::SATURDAY)->addDays(0); // Sábado (00:00)
    // $fimFimDeSemana = Carbon::now()->startOfWeek(Carbon::SATURDAY)->addDays(6)->endOfDay(); // Sexta-Feira (23:59)

    // $totalPresencas = Presenca::whereBetween('data_missa', [$inicioFimDeSemana, $fimFimDeSemana])->count();

    // return [
    //     Stat::make('Presenças Confirmadas', $totalPresencas)
    //     ->description('Missa de Sábado e Domingo')
    //     ->descriptionIcon('heroicon-m-users')
    //     ->color('success')
    //     ->url(route('filament.admin.resources.presencas.index')),
    // ];
    }
}