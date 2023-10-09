@extends('pdf.layouts.layout-export')
@section('title', "Situation Generale")
@section('content')

<h4 class="situation-heading">Situation Pharmacie du {{$date_situation}}</h4>
<div class="table-container">
    <!-- Tableau de gauche (RECETTE) -->
    <div class="table-wrapper left">
        <table class="custom-table">
            <!-- En-tête -->
            <tr>
                <th>DESIGNATION</th>
                <th>MONTANT</th>
            </tr>
            <!-- Contenu -->
            <!-- ... Votre boucle foreach existante ... -->
            {{$montant_total = 0}}
            @foreach($data as $sum)
                {{$montant_total = $montant_total + $sum->montant }}
            @endforeach
            <tr>
                <td><center> {{ \App\Models\Outil::toUpperCase('pharmacie')}}</center></td>
                <td>{{\App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)}}</td>
            </tr>
            {{-- <tr>
                <td colspan="2">
                    <div>
                        <p class="badge" style="line-height:15px;">Total</p>
                        <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_total, false, false)}}</p>
                    </div>
                </td>
            </tr> --}}
        </table>
    </div>
</div>

<!-- ... Le reste de votre modèle ... -->

@endsection
