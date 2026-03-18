<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkAcessoResource\Pages;
use App\Filament\Resources\LinkAcessoResource\RelationManagers;
use App\Models\LinkAcesso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Filament\Support\Enums\FontWeight;

class LinkAcessoResource extends Resource
{
    protected static ?string $model = LinkAcesso::class;

    protected static ?string $modelLabel = 'Link de Acesso';
    protected static ?string $pluralModelLabel = 'Links de Acesso';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationGroup = 'Utilitários';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Section::make('Detalhes do Link')
            ->schema([
                Forms\Components\TextInput::make('descricao')
                ->label('Descrição')
                ->placeholder('Ex: Link Domingo Manhã')
                ->maxLength(255)
                ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('expira_em')
                ->label('Expira em (Opcional)')
                ->helperText('Se vazio, o link nunca expirará automaticamente.'),
                Forms\Components\Toggle::make('is_ativo')
                ->label('Link Ativo')
                ->default(true),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('descricao')
            ->label('Descrição')
            ->searchable()
            ->weight(FontWeight::Bold),
            Tables\Columns\TextColumn::make('acessos')
            ->label('Qtd. Acessos')
            ->numeric()
            ->sortable()
            ->badge()
            ->color('success'),
            Tables\Columns\TextColumn::make('expira_em')
            ->label('Vencimento')
            ->dateTime('d/m/Y H:i')
            ->sortable(),
            Tables\Columns\IconColumn::make('is_ativo')
            ->label('Ativo')
            ->boolean(),
        ])
            ->defaultSort('created_at', 'desc')
            ->filters([
            //
        ])
            ->actions([
            Tables\Actions\Action::make('copiar')
            ->label('Copiar Link')
            ->icon('heroicon-o-clipboard-document')
            ->color('gray')
            ->extraAttributes(function (LinkAcesso $record): array {
            $url = url('/presenca?ref=' . $record->hash);
            return [
                    'x-on:click.prevent' => "
                        navigator.clipboard.writeText('{$url}')
                            .then(() => { \$dispatch('notify', { message: 'Link copiado para a área de transferência!', type: 'success' }); })
                            .catch(() => { window.prompt('Copie o link:', '{$url}'); });
                    ",
                ];
        })
            ->action(fn() => null),
            Tables\Actions\Action::make('qrcode')
            ->label('QR Code')
            ->icon('heroicon-o-qr-code')
            ->modalHeading('QR Code para Check-in')
            ->modalContent(fn(LinkAcesso $record) => view('filament.modals.qr-code', ['record' => $record]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Fechar'),
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
            'index' => Pages\ListLinkAcessos::route('/'),
            // 'create' => Pages\CreateLinkAcesso::route('/create'),
            // 'edit' => Pages\EditLinkAcesso::route('/{record}/edit'),
        ];
    }
}