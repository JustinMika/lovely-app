<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Liste des Clients</title>
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
        .summary-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }
        .stat-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }
        .clients-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .clients-table th, .clients-table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
        }
        .clients-table th {
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
        .client-tier {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .tier-vip {
            background: #fef3c7;
            color: #92400e;
        }
        .tier-regular {
            background: #dbeafe;
            color: #1e40af;
        }
        .tier-new {
            background: #f3e8ff;
            color: #7c3aed;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">Lovely App</div>
        <div class="report-title">Liste des Clients avec Historique</div>
        <div style="font-size: 14px; color: #6b7280; margin-top: 5px;">Généré le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>

    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-value">{{ $clients->count() }}</div>
            <div class="stat-label">Total Clients</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $clients->where('ventes_count', '>', 0)->count() }}</div>
            <div class="stat-label">Clients Actifs</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $clients->where('ventes_count', '>=', 5)->count() }}</div>
            <div class="stat-label">Clients VIP (5+ achats)</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ number_format($clients->sum(function($client) { return $client->ventes->sum('total'); }), 0) }} €</div>
            <div class="stat-label">CA Total Clients</div>
        </div>
    </div>

    <div class="section-title">Liste Complète des Clients</div>
    
    <table class="clients-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Contact</th>
                <th class="text-center">Nb Achats</th>
                <th class="text-right">Total Dépensé</th>
                <th class="text-right">Panier Moyen</th>
                <th class="text-center">Catégorie</th>
                <th>Dernière Visite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            @php
                $totalDepense = $client->ventes->sum('total');
                $panierMoyen = $client->ventes_count > 0 ? $totalDepense / $client->ventes_count : 0;
                $derniereVisite = $client->ventes->max('created_at');
            @endphp
            <tr>
                <td><strong>{{ $client->nom }}</strong></td>
                <td>
                    @if($client->telephone)
                        📞 {{ $client->telephone }}<br>
                    @endif
                    @if($client->email)
                        ✉️ {{ $client->email }}
                    @endif
                </td>
                <td class="text-center">{{ $client->ventes_count }}</td>
                <td class="text-right">{{ number_format($totalDepense, 2) }} €</td>
                <td class="text-right">{{ number_format($panierMoyen, 2) }} €</td>
                <td class="text-center">
                    @if($client->ventes_count >= 5)
                        <span class="client-tier tier-vip">VIP</span>
                    @elseif($client->ventes_count >= 2)
                        <span class="client-tier tier-regular">Régulier</span>
                    @elseif($client->ventes_count >= 1)
                        <span class="client-tier tier-new">Nouveau</span>
                    @else
                        <span style="color: #6b7280;">Prospect</span>
                    @endif
                </td>
                <td>
                    @if($derniereVisite)
                        {{ $derniereVisite->format('d/m/Y') }}
                    @else
                        <span style="color: #6b7280;">Jamais</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Top 10 des meilleurs clients -->
    @php
        $topClients = $clients->sortByDesc(function($client) {
            return $client->ventes->sum('total');
        })->take(10);
    @endphp

    @if($topClients->count() > 0)
    <div class="section-title">Top 10 des Meilleurs Clients</div>
    <table class="clients-table">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Nom</th>
                <th class="text-center">Nb Achats</th>
                <th class="text-right">Total Dépensé</th>
                <th class="text-right">Contribution CA</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCA = $clients->sum(function($client) { return $client->ventes->sum('total'); });
            @endphp
            @foreach($topClients as $index => $client)
            @php
                $clientCA = $client->ventes->sum('total');
                $contribution = $totalCA > 0 ? ($clientCA / $totalCA) * 100 : 0;
            @endphp
            <tr>
                <td><strong>{{ $index + 1 }}</strong></td>
                <td>{{ $client->nom }}</td>
                <td class="text-center">{{ $client->ventes_count }}</td>
                <td class="text-right">{{ number_format($clientCA, 2) }} €</td>
                <td class="text-right">{{ number_format($contribution, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Rapport généré le {{ now()->format('d/m/Y à H:i') }}</p>
        <p>Lovely App - Système de Gestion Commerciale</p>
    </div>
</body>
</html>
