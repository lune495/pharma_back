<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistique des Modules</title>
    <style>
        /* ----------- RESET DE BASE ----------- */
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            margin: 20px;
            color: #333;
            font-size: 13px;
        }

        /* ----------- EN-TÊTE ----------- */
        h1 {
            text-align: center;
            color: #0a3d62;
            text-transform: uppercase;
            border-bottom: 3px solid #0a3d62;
            padding-bottom: 5px;
            margin-bottom: 25px;
        }

        /* ----------- TABLE GLOBALE ----------- */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #0a3d62;
            color: white;
            text-transform: uppercase;
            font-size: 12px;
        }
        tr:nth-child(even) {
            background-color: #f3f3f3;
        }

        /* ----------- MODULE TITRE ----------- */
        .module-title {
            background-color: #82ccdd;
            color: #0a3d62;
            font-weight: bold;
            padding: 8px 10px;
            margin-top: 20px;
            border-radius: 5px;
        }

        /* ----------- AUCUNE DONNÉE ----------- */
        .no-data {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 10px;
        }

        /* ----------- FOOTER ----------- */
        footer {
            text-align: center;
            font-size: 10px;
            color: #888;
            position: fixed;
            bottom: 10px;
            width: 100%;
        }
    </style>
</head>
<body>

    <h1>Statistique des B.L par services </h1>

    @foreach($modules as $module)
        <div class="module-title">
            {{ $module->nom }} 
            <span style="float:right; font-size:12px;">
                @if($module->rdv_exist)
                    ✅ RDV Actif
                @else
                    ❌ Aucun RDV
                @endif
            </span>
        </div>

        @if($module->sortie_stocks->isEmpty())
            <p class="no-data">Aucune sortie de stock enregistrée pour ce module.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Référence</th>
                        <th>Date de sortie</th>
                        <th>ID utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($module->sortie_stocks as $index => $sortie)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sortie->ref }}</td>
                            <td>{{ \Carbon\Carbon::parse($sortie->created_at)->format('d/m/Y H:i') }}</td>
                            <td>{{ $sortie->user_id ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <footer>
        Généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }} - Système de gestion des stocks
    </footer>

</body>
</html>
