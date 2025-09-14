<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    /**
     * Display stock overview.
     */
    public function view(): View
    {
        $metrics = [
            'total_articles' => 1245,
            'low_stock_alerts' => 23,
            'total_value' => 125430,
            'movements_today' => 45
        ];

        return view('pages.stock.view', compact('metrics'));
    }

    /**
     * Display stock alerts.
     */
    public function alerts(): View
    {
        // TODO: Implement low stock alerts logic
        $alerts = collect(); // Placeholder for alerts
        
        return view('pages.stock.alerts', compact('alerts'));
    }

    /**
     * Display stock movements.
     */
    public function movements(): View
    {
        // TODO: Implement stock movements logic
        $movements = collect(); // Placeholder for movements
        
        return view('pages.stock.movements', compact('movements'));
    }

    /**
     * Update stock quantity for an article.
     */
    public function updateQuantity(Request $request, string $articleId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'movement_type' => 'required|in:in,out,adjustment',
            'reason' => 'nullable|string|max:255',
        ]);

        // TODO: Implement stock update logic
        // $article = Article::findOrFail($articleId);
        // StockMovement::create([...]);

        return redirect()->back()
            ->with('success', 'Stock mis à jour avec succès.');
    }
}
