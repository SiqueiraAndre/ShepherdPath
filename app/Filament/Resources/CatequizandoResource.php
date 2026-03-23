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

class CatequizandoResource extends Resource
{
    protected static ?string $model = Catequizando::class;
    protected static ?string $navigationGroup = 'Cadastros';
    protected static ?string $navigationIcon = 'heroicon-m-academic-cap';
    protected static ?string $modelLabel = 'Catequizandos';

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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('etapa.nome')
                    ->label('Etapa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('catequista.nomes')
                    ->label('Catequista')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
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
            'index' => Pages\ListCatequizandos::route('/'),
            // 'create' => Pages\CreateCatequizando::route('/create'),
            // 'edit' => Pages\EditCatequizando::route('/{record}/edit'),
        ];
    }
}