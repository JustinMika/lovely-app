<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LotResource\Pages;
use App\Filament\Resources\LotResource\RelationManagers;
use App\Models\Lot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LotResource extends Resource
{
    protected static ?string $model = Lot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('approvisionnement_id')
                    ->relationship('approvisionnement', 'id')
                    ->required(),
                Forms\Components\Select::make('article_id')
                    ->relationship('article', 'id')
                    ->required(),
                Forms\Components\Select::make('ville_id')
                    ->relationship('ville', 'id')
                    ->required(),
                Forms\Components\TextInput::make('numero_lot')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantite_initiale')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('quantite_restante')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('seuil_alerte')
                    ->required()
                    ->numeric()
                    ->default(10),
                Forms\Components\TextInput::make('prix_achat')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('prix_vente')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date_arrivee')
                    ->required(),
                Forms\Components\DatePicker::make('date_expiration'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('approvisionnement.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('article.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ville.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_lot')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantite_initiale')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantite_restante')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seuil_alerte')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_achat')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_vente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_arrivee')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_expiration')
                    ->date()
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
            'index' => Pages\ListLots::route('/'),
            'create' => Pages\CreateLot::route('/create'),
            'edit' => Pages\EditLot::route('/{record}/edit'),
        ];
    }
}
