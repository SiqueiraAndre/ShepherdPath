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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PresencaResource extends Resource
{
    protected static ?string $model = Presenca::class;

    protected static ?string $modelLabel = 'Presença';

    protected static ?string $pluralModelLabel = 'Presenças';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->defaultSort('data_missa', 'desc')
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'create' => Pages\CreatePresenca::route('/create'),
            'edit' => Pages\EditPresenca::route('/{record}/edit'),
        ];
    }
}
