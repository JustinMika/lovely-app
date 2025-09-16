<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Lot;
use Illuminate\Support\Facades\DB;

class StockAlertsWidget extends BaseWidget
{
    protected static ?string $heading = 'Alertes Stock';
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lot::query()
                    ->with(['article', 'ville'])
                    ->where('quantite_restante', '<=', DB::raw('seuil_alerte'))
                    ->orWhere('date_expiration', '<=', now()->addDays(30))
            )
            ->columns([
                Tables\Columns\TextColumn::make('article.designation')
                    ->label('Article')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('numero_lot')
                    ->label('N° Lot')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('quantite_restante')
                    ->label('Stock Restant')
                    ->badge()
                    ->color(fn ($record) => $record->quantite_restante <= $record->seuil_alerte ? 'danger' : 'warning'),
                    
                Tables\Columns\TextColumn::make('seuil_alerte')
                    ->label('Seuil Alerte'),
                    
                Tables\Columns\TextColumn::make('date_expiration')
                    ->label('Expiration')
                    ->date()
                    ->badge()
                    ->color(fn ($record) => $record->date_expiration <= now() ? 'danger' : 
                           ($record->date_expiration <= now()->addDays(7) ? 'warning' : 'success')),
                    
                Tables\Columns\TextColumn::make('ville.nom')
                    ->label('Ville')
                    ->sortable(),
            ])
            ->defaultSort('quantite_restante', 'asc');
    }
}
