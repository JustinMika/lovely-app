<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class ArticleExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'designation');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = Article::with(['lots']);

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('designation', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Appliquer le tri
        $articles = $query->orderBy($sortField, $sortDirection)->get();

        // Calculer les statistiques
        $totalArticles = $articles->count();
        $articlesActifs = $articles->where('actif', true)->count();
        $articlesInactifs = $articles->where('actif', false)->count();
        $articlesAvecStock = $articles->filter(function ($article) {
            return $article->lots->sum('quantite_restante') > 0;
        })->count();
        $valeurTotaleStock = $articles->sum(function ($article) {
            return $article->lots->sum(function ($lot) {
                return $lot->quantite_restante * $lot->prix_vente;
            });
        });
        $quantiteTotaleStock = $articles->sum(function ($article) {
            return $article->lots->sum('quantite_restante');
        });

        $stats = [
            'total_articles' => $totalArticles,
            'articles_actifs' => $articlesActifs,
            'articles_inactifs' => $articlesInactifs,
            'articles_avec_stock' => $articlesAvecStock,
            'articles_sans_stock' => $totalArticles - $articlesAvecStock,
            'valeur_totale_stock' => $valeurTotaleStock,
            'quantite_totale_stock' => $quantiteTotaleStock,
        ];

        // Créer le PDF avec FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête
        $pdf->Cell(0, 10, 'Liste des Articles', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Genere le ' . date('d/m/Y à H:i'), 0, 1, 'C');
        $pdf->Ln(10);

        // Statistiques
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Statistiques Generales', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, 'Total articles:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['total_articles']), 0, 1);
        $pdf->Cell(50, 6, 'Articles actifs:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['articles_actifs']), 0, 1);
        $pdf->Cell(50, 6, 'Articles inactifs:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['articles_inactifs']), 0, 1);
        $pdf->Cell(50, 6, 'Avec stock:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['articles_avec_stock']), 0, 1);
        $pdf->Cell(50, 6, 'Valeur stock totale:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['valeur_totale_stock'], 0, ',', ' ') . ' FCFA', 0, 1);
        $pdf->Cell(50, 6, 'Quantite totale:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['quantite_totale_stock']), 0, 1);
        $pdf->Ln(10);

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(70, 8, 'Designation', 1, 0, 'L', true);
        $pdf->Cell(50, 8, 'Description', 1, 0, 'L', true);
        $pdf->Cell(20, 8, 'Statut', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Stock', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Valeur', 1, 1, 'C', true);

        // Données
        $pdf->SetFont('Arial', '', 9);
        foreach ($articles as $article) {
            $stockTotal = $article->lots->sum('quantite_restante');
            $valeurStock = $article->lots->sum(function ($lot) {
                return $lot->quantite_restante * $lot->prix_vente;
            });

            $pdf->Cell(70, 6, substr($article->designation, 0, 30), 1, 0, 'L');
            $pdf->Cell(50, 6, substr($article->description ?: '-', 0, 20), 1, 0, 'L');
            $pdf->Cell(20, 6, $article->actif ? 'Actif' : 'Inactif', 1, 0, 'C');
            $pdf->Cell(25, 6, number_format($stockTotal), 1, 0, 'C');
            $pdf->Cell(25, 6, number_format($valeurStock, 0, ',', ' '), 1, 1, 'R');
        }

        // Footer avec informations de filtrage
        if (!empty($search)) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(0, 4, 'Filtre applique: ' . $search, 0, 1);
        }

        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 4, 'Tri: ' . $sortField . ' (' . ($sortDirection === 'asc' ? 'croissant' : 'decroissant') . ')', 0, 1);

        // Retourner le PDF
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="articles_' . date('Y-m-d_H-i-s') . '.pdf"');
    }

    public function exportExcel(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'designation');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = Article::with(['lots']);

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('designation', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Appliquer le tri
        $articles = $query->orderBy($sortField, $sortDirection)->get();

        // Créer un fichier CSV simple
        $filename = 'articles_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($articles) {
            $file = fopen('php://output', 'w');

            // En-tête CSV
            fputcsv($file, ['Designation', 'Description', 'Statut', 'Stock Total', 'Valeur Stock', 'Date Creation']);

            // Données
            foreach ($articles as $article) {
                $stockTotal = $article->lots->sum('quantite_restante');
                $valeurStock = $article->lots->sum(function ($lot) {
                    return $lot->quantite_restante * $lot->prix_vente;
                });

                fputcsv($file, [
                    $article->designation,
                    $article->description ?: '',
                    $article->actif ? 'Actif' : 'Inactif',
                    $stockTotal,
                    $valeurStock,
                    $article->created_at->format('d/m/Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
