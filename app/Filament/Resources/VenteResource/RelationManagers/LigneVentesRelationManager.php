<?php

namespace App\Filament\Resources\VenteResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LigneVentesRelationManager extends RelationManager
{
	protected static string $relationship = 'ligneVentes';

	public function form(Form $form): Form
	{
		return $form
			->schema([
				Forms\Components\Select::make('lot_id')
					->label('Lot')
					->relationship('lot', 'numero_lot')
					->searchable()
					->preload()
					->required(),
				Forms\Components\TextInput::make('quantite')
					->label('Quantité')
					->required()
					->numeric()
					->minValue(1),
				Forms\Components\TextInput::make('prix_unitaire')
					->label('Prix unitaire (€)')
					->required()
					->numeric()
					->step(0.01)
					->prefix('€'),
				Forms\Components\TextInput::make('remise')
					->label('Remise (€)')
					->numeric()
					->step(0.01)
					->default(0.00)
					->prefix('€'),
			]);
	}

	public function table(Table $table): Table
	{
		return $table
			->recordTitleAttribute('lot.numero_lot')
			->columns([
				Tables\Columns\TextColumn::make('lot.article.designation')
					->label('Article')
					->searchable(),
				Tables\Columns\TextColumn::make('lot.numero_lot')
					->label('N° Lot')
					->searchable(),
				Tables\Columns\TextColumn::make('quantite')
					->label('Quantité')
					->numeric(),
				Tables\Columns\TextColumn::make('prix_unitaire')
					->label('Prix unitaire')
					->money('EUR'),
				Tables\Columns\TextColumn::make('remise')
					->label('Remise')
					->money('EUR'),
				Tables\Columns\TextColumn::make('total')
					->label('Total')
					->getStateUsing(fn($record) => ($record->prix_unitaire * $record->quantite) - $record->remise)
					->money('EUR'),
			])
			->filters([
				//
			])
			->headerActions([
				Tables\Actions\CreateAction::make(),
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
}
