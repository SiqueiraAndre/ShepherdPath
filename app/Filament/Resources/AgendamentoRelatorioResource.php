<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgendamentoRelatorioResource\Pages;
use App\Models\AgendamentoRelatorio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;

class AgendamentoRelatorioResource extends Resource
{
    protected static ?string $model = AgendamentoRelatorio::class;

    protected static ?string $modelLabel = 'Agendamento de Relatório';

    protected static ?string $pluralModelLabel = 'Agendamentos de Relatório';

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Utilitários';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Section::make('Destinatários')
            ->icon('heroicon-o-users')
            ->schema([
                Forms\Components\TagsInput::make('destinatarios')
                ->label('E-mails dos destinatários')
                ->placeholder('Digite um e-mail e pressione Enter')
                ->helperText('Pressione Enter após cada endereço de e-mail.')
                ->columnSpanFull()
                ->required(),
            ]),

            Forms\Components\Section::make('Agendamento')
            ->icon('heroicon-o-clock')
            ->columns(2)
            ->schema([
                Forms\Components\DateTimePicker::make('data_envio')
                ->label('Data e hora de envio')
                ->seconds(false)
                ->native(false)
                ->default(fn () => now()->next(\Carbon\Carbon::MONDAY)->setTime(10, 0))
                ->required(),
                Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'pendente' => 'Pendente',
                    'enviado' => 'Enviado',
                    'falhou' => 'Falhou',
                ])
                ->default('pendente')
                ->required()
                ->visibleOn('edit'),
            ]),

            Forms\Components\Section::make('Período do Relatório')
            ->icon('heroicon-o-calendar-days')
            ->columns(2)
            ->description('Defina o intervalo de datas que o relatório de presenças deve cobrir.')
            ->schema([
                Forms\Components\DatePicker::make('periodo_inicio')
                ->label('De')
                ->native(false)
                ->default(fn () => now()->next(\Carbon\Carbon::MONDAY)->subDays(2))
                ->required(),
                Forms\Components\DatePicker::make('periodo_fim')
                ->label('Até')
                ->native(false)
                ->default(fn () => now()->next(\Carbon\Carbon::MONDAY)->subDays(1))
                ->required()
                ->afterOrEqual('periodo_inicio'),
            ]),

            Forms\Components\Section::make('Conteúdo do E-mail')
            ->icon('heroicon-o-envelope-open')
            ->schema([
                Forms\Components\TextInput::make('assunto')
                ->label('Assunto')
                ->maxLength(255)
                ->columnSpanFull()
                ->required(),
                Forms\Components\Textarea::make('mensagem')
                ->label('Mensagem')
                ->rows(5)
                ->columnSpanFull()
                ->required(),
                Forms\Components\Placeholder::make('anexo_info')
                ->label('Anexo')
                ->content('O relatório de presenças do período selecionado será gerado automaticamente em PDF e anexado ao e-mail.')
                ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('destinatarios')
            ->label('Destinatários')
            ->formatStateUsing(fn($state) => implode(', ', (array)$state))
            ->limit(50)
            ->tooltip(fn($record) => implode("\n", (array)$record->destinatarios))
            ->searchable(),
            Tables\Columns\TextColumn::make('data_envio')
            ->label('Data de Envio')
            ->dateTime('d/m/Y H:i')
            ->sortable(),
            Tables\Columns\TextColumn::make('periodo_inicio')
            ->label('Período')
            ->formatStateUsing(
        fn($state, $record) =>
        $record->periodo_inicio->format('d/m/Y') . ' → ' . $record->periodo_fim->format('d/m/Y')
        ),
            Tables\Columns\TextColumn::make('assunto')
            ->label('Assunto')
            ->limit(40)
            ->searchable(),
            Tables\Columns\BadgeColumn::make('status')
            ->label('Status')
            ->colors([
                'warning' => 'pendente',
                'success' => 'enviado',
                'danger' => 'falhou',
            ])
            ->formatStateUsing(fn($state) => match ($state) {
            'pendente' => 'Pendente',
            'enviado' => 'Enviado',
            'falhou' => 'Falhou',
            default => $state,
        }),
            Tables\Columns\TextColumn::make('enviado_em')
            ->label('Enviado em')
            ->dateTime('d/m/Y H:i')
            ->placeholder('—')
            ->sortable(),
        ])
            ->defaultSort('data_envio', 'asc')
            ->filters([
            Tables\Filters\SelectFilter::make('status')
            ->label('Filtrar por Status')
            ->options([
                'pendente' => 'Pendente',
                'enviado' => 'Enviado',
                'falhou' => 'Falhou',
            ]),
        ])
            ->actions([
            ActionGroup::make([
                Tables\Actions\EditAction::make()
                ->label('Editar'),
                Tables\Actions\Action::make('reenviar')
                ->label('Reenviar')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reagendar envio')
                ->modalDescription('O status será redefinido para "Pendente" e o e-mail será enviado novamente na próxima execução do agendador.')
                ->visible(fn($record) => $record->status !== 'pendente')
                ->action(function ($record) {
            $record->update([
                        'status' => 'pendente',
                        'enviado_em' => null,
                        'erro' => null,
                    ]);
            Notification::make()
                    ->title('Agendamento reativado com sucesso.')
                    ->success()
                    ->send();
        }),
                Tables\Actions\DeleteAction::make(),
            ])])
            ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ])
            ;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAgendamentosRelatorio::route('/'),
            'create' => Pages\CreateAgendamentosRelatorio::route('/create'),
            'edit' => Pages\EditAgendamentosRelatorio::route('/{record}/edit'),
        ];
    }
}