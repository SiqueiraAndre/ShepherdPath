<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatequistaResource\Pages;
use App\Filament\Resources\CatequistaResource\RelationManagers;
use App\Models\Catequista;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CatequistaResource extends Resource
{
    protected static ?string $model = Catequista::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Cadastros';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nomes')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('etapa_id')
                    ->relationship('etapa', 'nome')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('etapa.nome')
                    ->label('Etapa')
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
            'index' => Pages\ListCatequistas::route('/'),
            // 'create' => Pages\CreateCatequista::route('/create'),
            // 'edit' => Pages\EditCatequista::route('/{record}/edit'),
        ];
    }
}