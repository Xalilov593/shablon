<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Filament\Resources\RoleResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationGroup = 'Foydalanuvchi sozlamalari';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Foydalanuvchi roli';
    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->label('Nomi')
                        ->placeholder('Nomi')
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('permissions')
                        ->multiple()
                        -> label('Ruxsatni tanlang')
                        ->placeholder('Ruxsatni tanlang')
                        ->relationship('permissions', 'name')
                        ->preload()
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                ->label('Rol nomi'),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->label('Ruxsat nomi')
                    ->limit(50)
                    ->separator(', '),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tarixi')
                    ->sortable()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('name', '!=', 'Super-Admin');
    }
}
