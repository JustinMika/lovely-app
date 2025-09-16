<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenteResource\Pages;
use App\Filament\Resources\VenteResource\RelationManagers;
use App\Models\Vente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VenteResource extends Resource
{
	protected static ?string $model = Vente::class;

	protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

	protected static ?string $navigationGroup = 'Ventes';

	protected static ?int $navigationSort = 1;

	public static function form(Form $form): Form
	{
		return $form
			->schema([
				Forms\Components\Select::make('client_id')
					->label('Client')
					->relationship('client', 'nom')
					->searchable()
					->preload()
					->required(),
				Forms\Components\Select::make('utilisateur_id')
					->label('Vendeur')
					->relationship('utilisateur', 'name')
					->default(auth()->id())
					->required(),
				Forms\Components\TextInput::make('total')
					->label('Total (€)')
					->required()
					->numeric()
					->step(0.01)
					->prefix('€'),
				Forms\Components\TextInput::make('remise_totale')
					->label('Remise totale (€)')
					->numeric()
					->step(0.01)
					->default(0.00)
					->prefix('€'),
				Forms\Components\TextInput::make('montant_paye')
					->label('Montant payé (€)')
					->required()
					->numeric()
					->step(0.01)
					->prefix('€'),
			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				Tables\Columns\TextColumn::make('id')
					->label('N° Vente')
					->sortable(),
				Tables\Columns\TextColumn::make('client.nom')
					->label('Client')
					->searchable()
					->sortable(),
				Tables\Columns\TextColumn::make('utilisateur.name')
					->label('Vendeur')
					->sortable(),
				Tables\Columns\TextColumn::make('total')
					->label('Total')
					->money('EUR')
					->sortable(),
				Tables\Columns\TextColumn::make('montant_paye')
					->label('Payé')
					->money('EUR')
					->sortable(),
				Tables\Columns\TextColumn::make('montant_restant')
					->label('Restant')
					->getStateUsing(fn($record) => $record->total - $record->remise_totale - $record->montant_paye)
					->money('EUR')
					->color(fn($state) => $state > 0 ? 'danger' : 'success'),
				Tables\Columns\TextColumn::make('benefice')
					->label('Bénéfice')
					->money('EUR')
					->color('success')
					->toggleable(),
				Tables\Columns\TextColumn::make('created_at')
					->label('Date')
					->dateTime()
					->sortable(),
			])
			->filters([
				//
			])
			->actions([
				Tables\Actions\EditAction::make(),
				Tables\Actions\Action::make('print_invoice')
					->label('Imprimer Facture')
					->icon('heroicon-o-printer')
					->color('success')
					->url(fn($record) => route('vente.invoice.pdf', $record))
					->openUrlInNewTab(),
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
			RelationManagers\LigneVentesRelationManager::class,
		];
	}

	public static function getPages(): array
	{
		return [
			'index' => Pages\ListVentes::route('/'),
			'create' => Pages\CreateVente::route('/create'),
			'view' => Pages\ViewVente::route('/{record}'),
			'edit' => Pages\EditVente::route('/{record}/edit'),
		];
	}
}
