<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Ventes par Période</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #8b5cf6;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #8b5cf6;
        }
        .report-title {
            font-size: 20px;
            margin-top: 10px;
            color: #374151;
        }
        .period {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .summary-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .summary-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .sales-table th, .sales-table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
        }
        .sales-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin: 30px 0 15px 0;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .daily-summary {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .daily-title {
            font-weight: bold;
            color: #475569;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .chart-placeholder {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            color: #64748b;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div class="report-title">Rapport des Ventes par Période</div>
        <div class="period">Période: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Ventes</h3>
            <div class="value">{{ $ventes->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>Chiffre d'Affaires</h3>
            <div class="value">{{ number_format($ventes->sum('total'), 2) }} €</div>
        </div>
        <div class="summary-card">
            <h3>Panier Moyen</h3>
            <div class="value">{{ $ventes->count() > 0 ? number_format($ventes->sum('total') / $ventes->count(), 2) : 0 }} €</div>
        </div>
        <div class="summary-card">
            <h3>Clients Uniques</h3>
            <div class="value">{{ $ventes->unique('client_id')->count() }}</div>
        </div>
    </div>

    <!-- Résumé par jour -->
    <div class="section-title">Résumé des Ventes par Jour</div>
    @foreach($ventesParJour as $date => $ventesJour)
    <div class="daily-summary">
        <div class="daily-title">{{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}</div>
        <div style="display: flex; justify-content: space-between;">
            <span>{{ $ventesJour->count() }} vente(s)</span>
            <span><strong>{{ number_format($ventesJour->sum('total'), 2) }} €</strong></span>
        </div>
    </div>
    @endforeach

    <!-- Top 10 des articles les plus vendus -->
    <div class="section-title">Top 10 des Articles les Plus Vendus</div>
    <table class="sales-table">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Article</th>
                <th class="text-center">Quantité Vendue</th>
                <th class="text-right">Part des Ventes</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantite = $articlesVendus->sum('total_vendu');
            @endphp
            @foreach($articlesVendus as $index => $article)
            <tr>
                <td><strong>{{ $index + 1 }}</strong></td>
                <td>{{ $article->designation }}</td>
                <td class="text-center">{{ $article->total_vendu }}</td>
                <td class="text-right">{{ $totalQuantite > 0 ? number_format(($article->total_vendu / $totalQuantite) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Détail des ventes -->
    <div class="section-title">Détail des Ventes</div>
    <table class="sales-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Vendeur</th>
                <th class="text-right">Total</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Restant</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventes as $vente)
            <tr>
                <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $vente->client->nom }}</td>
                <td>{{ $vente->utilisateur->name }}</td>
                <td class="text-right">{{ number_format($vente->total, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_paye, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_restant, 2) }} €</td>
                <td class="text-center">
                    @if($vente->montant_restant <= 0)
                        <span style="color: #059669; font-weight: bold;">✓ Payé</span>
                    @else
                        <span style="color: #dc2626; font-weight: bold;">⚠ Impayé</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Analyse des performances -->
    <div class="section-title">Analyse des Performances</div>
    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="flex: 1; background: #f8fafc; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #374151;">Ventes Payées</h4>
            <div style="font-size: 20px; font-weight: bold; color: #059669;">
                {{ $ventes->where('montant_restant', '<=', 0)->count() }} / {{ $ventes->count() }}
            </div>
            <div style="color: #6b7280; font-size: 14px;">
                {{ $ventes->count() > 0 ? number_format(($ventes->where('montant_restant', '<=', 0)->count() / $ventes->count()) * 100, 1) : 0 }}% des ventes
            </div>
        </div>
        
        <div style="flex: 1; background: #f8fafc; padding: 20px; border-radius: 8px;">
            <h4 style="margin: 0 0 10px 0; color: #374151;">Créances</h4>
            <div style="font-size: 20px; font-weight: bold; color: #dc2626;">
                {{ number_format($ventes->sum('montant_restant'), 2) }} €
            </div>
            <div style="color: #6b7280; font-size: 14px;">
                {{ $ventes->where('montant_restant', '>', 0)->count() }} vente(s) impayée(s)
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Lovely App - Système de Gestion Commerciale</p>
    </div>
</body>
</html>
