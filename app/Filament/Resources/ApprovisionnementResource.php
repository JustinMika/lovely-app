<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovisionnementResource\Pages;
use App\Filament\Resources\ApprovisionnementResource\RelationManagers;
use App\Models\Approvisionnement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApprovisionnementResource extends Resource
{
    protected static ?string $model = Approvisionnement::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('fournisseur')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\Select::make('utilisateur_id')
                    ->relationship('utilisateur', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fournisseur')
                    ->searchable(),
                Tables\Columns\TextColumn::make('utilisateur.name')
                    ->numeric()
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
            ->filters([
                //
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
            'index' => Pages\ListApprovisionnements::route('/'),
            'create' => Pages\CreateApprovisionnement::route('/create'),
            'edit' => Pages\EditApprovisionnement::route('/{record}/edit'),
        ];
    }
}
