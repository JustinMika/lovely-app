<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche Client - {{ $client->nom }}</title>
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
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .report-title {
            font-size: 20px;
            margin-top: 10px;
            color: #374151;
        }
        .client-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .client-name {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
        }
        .info-value {
            color: #1f2937;
        }
        .stats-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }
        .stat-card {
            flex: 1;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .purchases-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .purchases-table th, .purchases-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        .purchases-table th {
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
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .status-paid {
            color: #059669;
            font-weight: bold;
        }
        .status-unpaid {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div class="report-title">Fiche Client Détaillée</div>
        <div style="font-size: 14px; color: #6b7280; margin-top: 5px;">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="client-info">
        <div class="client-name">{{ $client->nom }}</div>
        <div class="info-grid">
            <div>
                @if($client->telephone)
                <div class="info-item">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $client->telephone }}</span>
                </div>
                @endif
                @if($client->email)
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $client->email }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Client depuis:</span>
                    <span class="info-value">{{ $client->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <div>
                @if($derniereVisite)
                <div class="info-item">
                    <span class="info-label">Dernière visite:</span>
                    <span class="info-value">{{ $derniereVisite->format('d/m/Y') }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Statut:</span>
                    <span class="info-value">
                        @if($totalAchats >= 5)
                            <span style="color: #f59e0b; font-weight: bold;">Client VIP</span>
                        @elseif($totalAchats >= 2)
                            <span style="color: #3b82f6; font-weight: bold;">Client Régulier</span>
                        @elseif($totalAchats >= 1)
                            <span style="color: #8b5cf6; font-weight: bold;">Nouveau Client</span>
                        @else
                            <span style="color: #6b7280;">Prospect</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-cards">
        <div class="stat-card">
            <h3>Total Dépensé</h3>
            <div class="value">{{ number_format($totalDepense, 2) }} €</div>
        </div>
        <div class="stat-card">
            <h3>Nombre d'Achats</h3>
            <div class="value">{{ $totalAchats }}</div>
        </div>
        <div class="stat-card">
            <h3>Panier Moyen</h3>
            <div class="value">{{ number_format($panierMoyen, 2) }} €</div>
        </div>
        <div class="stat-card">
            <h3>Créances</h3>
            <div class="value">{{ number_format($client->ventes->sum('montant_restant'), 2) }} €</div>
        </div>
    </div>

    @if($client->ventes->count() > 0)
    <div class="section-title">Historique des Achats</div>
    <table class="purchases-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vendeur</th>
                <th class="text-right">Total</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Restant</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($client->ventes->sortByDesc('created_at') as $vente)
            <tr>
                <td>{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $vente->utilisateur->name }}</td>
                <td class="text-right">{{ number_format($vente->total, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_paye, 2) }} €</td>
                <td class="text-right">{{ number_format($vente->montant_restant, 2) }} €</td>
                <td class="text-center">
                    @if($vente->montant_restant <= 0)
                        <span class="status-paid">✓ Payé</span>
                    @else
                        <span class="status-unpaid">⚠ Impayé</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Articles achetés -->
    <div class="section-title">Articles Achetés</div>
    <table class="purchases-table">
        <thead>
            <tr>
                <th>Article</th>
                <th class="text-center">Quantité Totale</th>
                <th class="text-right">Montant Total</th>
                <th>Dernière Date</th>
            </tr>
        </thead>
        <tbody>
            @php
                $articlesAchetes = collect();
                foreach($client->ventes as $vente) {
                    foreach($vente->ligneVentes as $ligne) {
                        $designation = $ligne->lot->article->designation;
                        if(!$articlesAchetes->has($designation)) {
                            $articlesAchetes[$designation] = [
                                'quantite' => 0,
                                'montant' => 0,
                                'derniere_date' => null
                            ];
                        }
                        $articlesAchetes[$designation]['quantite'] += $ligne->quantite;
                        $articlesAchetes[$designation]['montant'] += ($ligne->prix_unitaire * $ligne->quantite) - $ligne->remise;
                        if(!$articlesAchetes[$designation]['derniere_date'] || $vente->created_at > $articlesAchetes[$designation]['derniere_date']) {
                            $articlesAchetes[$designation]['derniere_date'] = $vente->created_at;
                        }
                    }
                }
            @endphp
            @foreach($articlesAchetes as $designation => $data)
            <tr>
                <td>{{ $designation }}</td>
                <td class="text-center">{{ $data['quantite'] }}</td>
                <td class="text-right">{{ number_format($data['montant'], 2) }} €</td>
                <td>{{ $data['derniere_date']->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="section-title">Historique des Achats</div>
    <p class="text-center" style="padding: 40px; color: #6b7280;">Aucun achat enregistré pour ce client.</p>
    @endif

    <div class="footer">
        <p>Fiche client générée le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Lovely App - Système de Gestion Commerciale</p>
    </div>
</body>
</html>
