<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PresencaResource\Pages;
use App\Filament\Resources\PresencaResource\RelationManagers;
use App\Models\Presenca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresencaResource extends Resource
{
    protected static ?string $model = Presenca::class;

    protected static ?string $modelLabel = 'Presença';

    protected static ?string $pluralModelLabel = 'Presenças';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Utilitários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('aluno_id')
                    ->relationship('aluno', 'nome_completo')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('missa_id')
                    ->relationship('missa', 'descricao')
                    ->required(),
                Forms\Components\DatePicker::make('data_missa')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('aluno.nome_completo')
                    ->label('Aluno(a)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('aluno.catequista.nomes')
                    ->label('Catequista')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('missa.descricao')
                    ->label('Missa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_missa')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('missa')
                    ->relationship('missa', 'descricao')
                    ->label('Filtrar por Missa'),
                Tables\Filters\SelectFilter::make('catequista')
                    ->relationship('aluno.catequista', 'nomes')
                    ->label('Filtrar por Catequista'),
                Tables\Filters\Filter::make('data_missa')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('De'),
                        Forms\Components\DatePicker::make('created_until')->label('Até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data_missa', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('data_missa', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Data Inicial: ' . \Carbon\Carbon::parse($data['created_from'])->format('d/m/Y'))
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Data Final: ' . \Carbon\Carbon::parse($data['created_until'])->format('d/m/Y'))
                                ->removeField('created_until');
                        }
                        return $indicators;
                    })
            ])
            ->headerActions([
                Tables\Actions\Action::make('relatorio_fim_semana')
                    ->label('Relatório do Final de Semana')
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('primary')
                    ->form([
                        Forms\Components\DatePicker::make('de')
                            ->label('De')
                            ->default(\Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY))
                            ->required(),
                        Forms\Components\DatePicker::make('ate')
                            ->label('Até')
                            ->default(\Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays(6))
                            ->required(),
                    ])
                    ->modalHeading('Gerar Relatório do Final de Semana')
                    ->modalSubmitActionLabel('Gerar PDF')
                    ->action(function (array $data) {
                        $de  = \Carbon\Carbon::parse($data['de'])->startOfDay();
                        $ate = \Carbon\Carbon::parse($data['ate'])->endOfDay();

                        $presencas = Presenca::with(['aluno.catequista', 'missa'])
                            ->whereBetween('data_missa', [$de, $ate])
                            ->get();

                        if ($presencas->isEmpty()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Nenhuma presença encontrada no período selecionado.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $agrupamento = $presencas
                            ->sortBy(fn ($p) => optional($p->aluno)->nome_completo)
                            ->groupBy(fn ($p) => optional(optional($p->aluno)->catequista)->nomes ?? 'Sem Catequista');

                        $periodo = $de->format('d/m/Y') . ' a ' . $ate->format('d/m/Y');

                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('relatorios.presenca', [
                            'agrupamento' => $agrupamento,
                            'periodo'     => $periodo,
                        ]);

                        $nomeArquivo = 'relatorio_presenca_' . $de->format('d-m-Y') . '_a_' . $ate->format('d-m-Y') . '.pdf';

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $nomeArquivo,
                            ['Content-Type' => 'application/pdf']
                        );
                    }),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('exportar_excel')
                        ->label('Exportar para Excel')
                        ->icon('heroicon-o-table-cells')
                        ->color('success')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $export = new \App\Exports\PresencasExport($records->load(['aluno.catequista', 'missa']));
                            $nomeArquivo = 'presencas_' . now()->format('d-m-Y_H-i-s') . '.xlsx';
                            return \Maatwebsite\Excel\Facades\Excel::download($export, $nomeArquivo);
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('exportar_pdf')
                        ->label('Exportar para PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->load(['aluno.catequista', 'missa']);

                            $agrupamento = $records
                                ->sortBy(fn ($p) => optional($p->aluno)->nome_completo)
                                ->groupBy(fn ($p) => optional(optional($p->aluno)->catequista)->nomes ?? 'Sem Catequista');

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('relatorios.presenca', [
                                'agrupamento' => $agrupamento,
                                'periodo'     => now()->format('d/m/Y'),
                            ]);

                            $nomeArquivo = 'presencas_' . now()->format('d-m-Y_H-i-s') . '.pdf';

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                $nomeArquivo,
                                ['Content-Type' => 'application/pdf']
                            );
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPresencas::route('/'),
            // 'create' => Pages\CreatePresenca::route('/create'),
            // 'edit' => Pages\EditPresenca::route('/{record}/edit'),
        ];
    }
}