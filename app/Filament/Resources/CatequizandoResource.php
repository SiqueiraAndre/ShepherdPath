<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatequizandoResource\Pages;
use App\Filament\Resources\CatequizandoResource\RelationManagers;
use App\Models\Catequizando;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;

class CatequizandoResource extends Resource
{
    protected static ?string $model = Catequizando::class;
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationIcon = 'heroicon-m-academic-cap';
    protected static ?string $modelLabel = 'Catequizandos';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nome_completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('etapa_id')
                    ->relationship('etapa', 'nome')
                    ->required(),
                Forms\Components\Select::make('catequista_id')
                    ->relationship('catequista', 'nomes')
                    ->required(),
                Forms\Components\DatePicker::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->displayFormat('d/m/Y'),
                Forms\Components\TextInput::make('telefone')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(00) 00000-0000')                
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nome_responsavel')
                    ->label('Nome do Responsável')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                ->label('Nome')    
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('idade')
                    ->label('Idade')
                    ->sortable()
                    ->searchable(),                                     
                Tables\Columns\TextColumn::make('etapa.nome')
                    ->label('Etapa')
                    ->sortable()
                    ->searchable(),              
                Tables\Columns\TextColumn::make('catequista.nomes')
                    ->label('Catequista')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),    
Tables\Columns\TextColumn::make('nome_responsavel')
                    ->label('Responsável')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),                    
                Tables\Columns\TextColumn::make('telefone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('etapa')
                    ->relationship('etapa', 'nome')
                    ->label('Filtrar por Etapa'),
                Tables\Filters\SelectFilter::make('catequista')
                    ->relationship('catequista', 'nomes')
                    ->label('Filtrar por Catequista'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->dropdownPlacement('bottom-start')                
            ])            
            ->headerActions([
                \Filament\Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('baixar_modelo')
                        ->label('Baixar Modelo Excel')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function () {
                            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CatequizandosModeloExport, 'modelo-importacao-catequizandos.xlsx');
                        }),
                    Tables\Actions\Action::make('importar_planilha')
                        ->label('Importar Planilha')
                        ->icon('heroicon-o-document-arrow-up')
                        ->form([
                            \Filament\Forms\Components\FileUpload::make('arquivo_excel')
                                ->label('Arquivo Preenchido')
                                ->disk('local')
                                ->directory('imports')
                                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'])
                                ->required(),
                        ])
                        ->action(function (array $data) {
                            $filePath = storage_path('app/private/' . $data['arquivo_excel']);
                            if (!file_exists($filePath)) {
                                $filePath = storage_path('app/' . $data['arquivo_excel']);
                            }
                            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\CatequizandosImport, $filePath);
                            \Filament\Notifications\Notification::make()
                                ->title('Importação concluída!')
                                ->success()
                                ->send();
                        })
                ])
                ->label('Importar')
                ->icon('heroicon-m-arrow-up-tray')
                ->button()
                ->color('success'),
                \Filament\Tables\Actions\ActionGroup::make([
                    ExportAction::make()
                        ->label('Exportar Excel')
                        ->exports([
                            ExcelExport::make('excel')->fromTable()->withFilename('catequizandos_' . date('Y-m-d') . '.xlsx')->withWriterType(Excel::XLSX)->label('Exportar Planilha')
                        ]),
                    Tables\Actions\Action::make('export_pdf')
                        ->label('Exportar PDF')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function () {
                            $records = \App\Models\Catequizando::with('etapa', 'catequista')->get();
                            $pdf = Pdf::loadView('relatorios.catequizandos', ['records' => $records]);
                            return response()->streamDownload(fn () => print($pdf->output()), 'catequizandos_' . date('Y-m-d') . '.pdf');
                        })
                ])
                ->label('Exportar')
                ->icon('heroicon-m-arrow-down-tray')
                ->button()
                ->color('primary')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()
                        ->label('Exportar (Excel)')
                        ->exports([
                            ExcelExport::make('excel')->fromTable()->withFilename('catequizandos_selecionados.xlsx')->withWriterType(Excel::XLSX)->label('Exportar Planilha')
                        ]),
                    Tables\Actions\BulkAction::make('export_pdf_selecionados')
                        ->label('Exportar (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $pdf = Pdf::loadView('relatorios.catequizandos', ['records' => $records]);
                            return response()->streamDownload(fn () => print($pdf->output()), 'catequizandos_selecionados.pdf');
                        })
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
            'index' => Pages\ListCatequizandos::route('/'),
            // 'create' => Pages\CreateCatequizando::route('/create'),
            // 'edit' => Pages\EditCatequizando::route('/{record}/edit'),
        ];
    }
}