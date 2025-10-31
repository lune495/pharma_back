<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche d'inventaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            /* text-align: left; */
            text-align: center;       /* centrer horizontalement */
            vertical-align: middle;   /* centrer verticalement */
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .designation-produit{
            width: 30%;
        }
        .code-produit{
            width: 35%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fiche d'Inventaire PHARMACIE</h1>
        <p>Date: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="code-produit">Code Produit</th>
                <th class="designation-produit">Désignation</th>
                <th>Famille</th>
                <!-- <th>Stock Théorique</th> -->
                <th>Stock Physique</th>
                <!-- <th>Écart</th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($produits ?? [] as $produit)
            <tr>
                <td>{{ $produit->code == 'null' ? '-' : $produit->code }}</td>
                <td>{{ $produit->designation ?? '' }}</td>
                <td></td>
                <!-- <td>{{ $produit->stock_physique ?? 0 }}</td> -->
                <!-- <td>{{ ($produit->stock_physique ?? 0) - ($produit->stock_theorique ?? 0) }}</td> -->
                <td>{{ $produit->observations ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Inventaire réalisé par: ______________________</p>
        <p>Signature: ______________________</p>
        <p>Date: ______________________</p>
    </div>
</body>
</html>