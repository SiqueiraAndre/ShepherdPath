<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkAcessoResource\Pages;
use App\Models\LinkAcesso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Support\Enums\FontWeight;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkAcessoResource extends Resource
{
    protected static ?string $model = LinkAcesso::class;

    protected static ?string $modelLabel = 'Link de Presença';
    protected static ?string $pluralModelLabel = 'Links de Presença';
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
            ])->columns(2),
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
            // Coluna com clipboard nativo do Filament — clique no ícone copia o link
            Tables\Columns\TextColumn::make('link_acesso')
            ->label('Link')
            ->state(fn(LinkAcesso $record): string => url('/presenca?ref=' . $record->hash))
            ->icon('heroicon-o-link')
            ->copyable()
            ->copyMessage('Link copiado!')
            ->copyMessageDuration(1500)
            ->limit(38),
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
            // ->color('gray')
            ->action(function (LinkAcesso $record) {
            \Filament\Notifications\Notification::make()
                ->title('Link copiado!')
                ->success()
                ->send();
        })
            ->extraAttributes([
                'type' => 'button',
                'onclick' => "navigator.clipboard.writeText(this.closest('button, a').dataset.link); event.preventDefault(); event.stopPropagation();",
            ])
            ->extraAttributes(fn (LinkAcesso $record) => [
                'data-link' => url('/presenca?ref=' . $record->hash),
            ], merge: true),       
            
            Tables\Actions\Action::make('qrcode')
            ->label('QR Code')
            ->icon('heroicon-o-qr-code')
            ->modalHeading('QR Code para Check-in')
            ->modalContent(fn(LinkAcesso $record) => view('filament.modals.qr-code', ['record' => $record]))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Fechar'),
            // Tables\Actions\EditAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinkAcessos::route('/'),
            // 'create' => Pages\CreateLinkAcesso::route('/create'),
            // 'edit' => Pages\EditLinkAcesso::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // return static::$model::where('is_ativo', true)->count();
        return false;
    }    
}