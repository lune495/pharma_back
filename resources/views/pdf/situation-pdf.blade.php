<h4 class="situation-heading">Situation Pharmacie du {{$date_situation}}</h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <table class="custom-table">
            <!-- En-tête -->
            <thead>
                <tr>
                    <th>DESIGNATION</th>
                    <th>MONTANT</th>
                </tr>
            </thead>
            <tbody>
                <!-- Contenu -->
                {{$montant_total = 0}}
                @foreach($data as $sum)
                    {{$montant_total = $montant_total + $sum->montant }}
                @endforeach
                <tr>
                    <td><center>{{ \App\Models\Outil::toUpperCase('pharmacie')}}</center></td>
                    <td><center>{{\App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)}}</center></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style>
/* Général */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

/* Titre */
.situation-heading {
    text-align: center;
    font-size: 24px;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* Conteneur */
.table-container {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    max-width: 800px;
}

/* Tableau */
.custom-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 14px;
}

.custom-table th {
    background-color: #34495e;
    color: #fff;
    padding: 10px;
    text-transform: uppercase;
    border-bottom: 2px solid #ddd;
}

.custom-table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.custom-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.custom-table tr:hover {
    background-color: #f1f1f1;
}

/* Centrage du texte */
.custom-table td center {
    font-weight: bold;
    color: #34495e;
}

/* Badge (facultatif) */
.badge {
    background-color: #27ae60;
    color: #fff;
    display: inline-block;
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 12px;
    text-align: center;
    margin: 5px auto;
}
</style>
