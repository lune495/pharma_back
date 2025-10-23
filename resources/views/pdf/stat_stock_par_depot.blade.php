<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques des Stocks par Dépôt</title>
    <style>
        @page { margin: 40px 30px; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            background-color: #f9f9f9;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            color: #0A5275;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        h2 {
            text-align: center;
            color: #555;
            margin-top: 0;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
        }

        th, td {
            padding: 8px 10px;
            text-align: center;
        }

        th {
            background-color: #0A5275;
            color: white;
            text-transform: uppercase;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f1f6fa;
        }

        tr:hover {
            background-color: #eaf2f8;
        }

        td {
            border-bottom: 1px solid #ddd;
            font-size: 11.5px;
        }

        .footer {
            text-align: right;
            margin-top: 30px;
            font-size: 10px;
            color: #888;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            color: #fff;
        }

        .badge-green { background-color: #27ae60; }
        .badge-red { background-color: #c0392b; }
        .badge-blue { background-color: #2980b9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CHIFAA</h1>
        <h2>Statistiques des Stocks Dépôt {{ $depot }}</h2>
        <p>Période : Du {{ $dateDebut }} au {{ $dateFin }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID Produit</th>
                <th>Nom du Produit</th>
                <th>Quantité Initiale</th>
                <th>Total Entrées</th>
                <th>Total Sorties</th>
                <th>Transferts Entrants</th>
                <th>Transferts Sortants</th>
                <th>Stock Actuel</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock['produit_id'] }}</td>
                    <td>{{ $stock['nom'] }}</td>
                    <td>{{ number_format($stock['quantite_initiale'], 0, ',', ' ') }}</td>
                    <td class="badge badge-green">{{ number_format($stock['total_entrees'], 0, ',', ' ') }}</td>
                    <td class="badge badge-red">{{ number_format($stock['total_sorties'], 0, ',', ' ') }}</td>
                    <td>{{ number_format($stock['transferts_entrants'], 0, ',', ' ') }}</td>
                    <td>{{ number_format($stock['transferts_sortants'], 0, ',', ' ') }}</td>
                    <td class="badge badge-blue">
                        {{ number_format($stock['stock_actuel'], 0, ',', ' ') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Rapport généré automatiquement — {{ \Carbon\Carbon::now()->format('d/m/Y') }}
    </div>
</body>
</html>
