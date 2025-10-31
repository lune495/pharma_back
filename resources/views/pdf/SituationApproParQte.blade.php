<!DOCTYPE html>
<html>
    <head>
<style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 3px solid #007bff;
            display: inline-block;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }
        td {
            font-size: 13px;
            font-weight: bold;
            color: #555;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
</style>
    </head>
    <body>
    <div class="container">
        <h2 class="text-center text-primary">Rapport des Appro du 01-01-2024 au 31-12-2024</h2>
        <table class="table table-bordered table-striped mt-4">
            <thead class="bg-primary text-white">
                <tr>
                    <th>#</th>
                    <th>Produit</th>
                    <th>Quantité Acheté</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produits as $index => $produit)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $produit->designation }}</td>
                        <td class="text-center">{{ $produit->total_qte }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </body>
</html>