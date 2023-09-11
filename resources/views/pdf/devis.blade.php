{{-- @if (auth()->check()) --}}
@extends('pdf.layouts.layout-export')
@section('title', "PDF Facture commande")
@section('content')

    <table style="border: none; border: none;margin-top:2px;font-size: 11px">
        <tr>
            <td style="border: none">
                <p style="font-weight: bold;font-size: 14px">C.I.S SHOWROOM</p>
                <p style="font-size: 11px">Vente de Materiels de Plomberie et Sanitaire</p>
            </td>
        </tr>
        <tr  style="border: none">
            <td  style="border: none">
                <div style="" >
                    <p  style="text-align:left;line-height:5px"> OUEST FOIRE, TALLY WALLY N°21  </p>
                    <p style="text-align:left;line-height:5px"> +221 77 348 15 82</p>
                    <p style="text-align:left;line-height:5px"> +221 77 597 55 21</p>
                </div>
            </td>
            <td style="border:none;">
                <div style="border-left: 3px solid black">
                    <p style="text-align:left ; margin-left:15px;line-height:5px ">www.ccps.sn</p>
                    <p style="text-align:left ; margin-left:15px;line-height:5px ">Instagram:  @ccps</p>
                    <p style="text-align:left ; margin-left:15px;line-height:5px ">email:  ccpsvdn@gmail.com</p>
                </div>
            </td>
            <td style="border:none;"></td>
            <td style="border:none;"></td>
        </tr>
    </table>

    <table style="border: none;font-size: 11px; margin-top:0px">
        <tr  style="border: none">
            <td style="border: none;">
                <div>
                    <p class="badge" style="text-align:left;line-height:15px">Date</p>
                    <p style="text-align:left;line-height:5px">{{ $created_at_fr}}</p>
                    <p style="style=border-left: 2px solid white;border-bottom: 2px solid white"></p>
                    <p style="style=border-left: 2px solid white;border-bottom: 2px solid white"></p>
                </div>
            </td>
            <td style="border: none;"></td><td style="border: none;"></td><td style="border: none;"></td><td style="border: none;"></td><td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none;"></td>
            <td style="border: none">
                <div>
                    <p class="badge" style="text-align:left;line-height:15px">Client</p>
                    <p style="text-align:left;line-height:5px">{{ $client ?  "Nom: " .\App\Models\Outil::premereLettreMajuscule($client["nom_complet"]) : "CLIENT DE PASSAGE"}}</p>
                    <p style="text-align:left ;line-height:5px ">{{ $client ? "Téléphone: " . $client["telephone"] : " "}}</p>
                    <p style="text-align:left;line-height:5px;text-transform: capitalize "> {{ $client ? "Adresse:" . $client["adresse"] : ""}}</p>
                </div>
            </td>
            <td style="border:none;"></td>
        </tr>
    </table>

    <h2 style="margin:0">Devis N°  {{$numero ? $numero : "FA01"}}</h2>
    <br>
    <div class="static">
    <table class="table table-bordered">
        <tr>
            <th style="border:none"> <p class="badge">REF.</p> </th>
            <th style="border:none"> <p class="badge">DESIGNATION</p> </th>
            <th style="border:none"><p class="badge">QTE</p></th>
            <th style="border:none"><p class="badge">P.U</p></th>
            <th style="border:none"><p class="badge">REMISE</p></th>
            <th style="border:none"><p class="badge">MONTANT</p></th>
        </tr>
    <tbody style="border:none">
        {{$i = 0}}
        @foreach($proforma_produits as $proforma)
            {{$i++}}
            <tr {{ $i%2 == 1 ? "style=background-color:rgba(255,249,249,0.877);line-height:9px": "style=background-color:rgba(21,150,189,0.281);line-height:9px" }}>
                <td style="font-size:12px;padding: 6px;line-height:15px"> {{ \App\Models\Outil::premereLettreMajuscule($proforma["produit"] ? $proforma["produit"]["code"] : "No ref")}}</td>
                <td style="font-size:12px;padding: 6px;line-height:15px"><center> {{ \App\Models\Outil::premereLettreMajuscule($proforma["produit"] ? $proforma["produit"]["designation"] : "")}}</center></td>
                <td style="font-size:12px;padding: 6px"> <center>{{$proforma["qte"]}}</center></td>
                <td style="font-size:12px;padding: 6px"> <center>{{$proforma["prix_vente"]}}</center></td>
                <td style="font-size:12px;padding: 6px"> <center>{{($proforma["remise"] ? $proforma["remise"] : "-")}}</center></td>
                <td style="font-size:12px;padding: 6px"><center>{{\App\Models\Outil::formatPrixToMonetaire($proforma["montant_net"], false, false)}}</center></td>
            </tr>
        @endforeach

        <!--total-->
        <tr>
            {{-- <td colspan="1" style="border-left: 2px solid white;border-bottom: 2px solid white"></td> --}}
             {{-- <td>
                @if (isset($montant_ttc))
                
                    <p class="badge" style="line-height:15px">Total TTC</p>
                    <p style="line-height:5px">{{ \App\Models\Outil::formatPrixToMonetaire($montant_ttc, false, false)}}</p>
                @endif
            </td> --}}
            @if ($montant_ttc==0)
            <td colspan="1" style="border-left: 2px solid white;border-bottom: 2px solid white"></td>
            @endif
            @if ($montant_ttc!=0)
            <td>
            <div>
            @else
            <td>
            <div>
            @endif
                <p class="badge" style="line-height:15px;">Total HT</p>
                <p style="line-height:5px;text-align:center">{{ \App\Models\Outil::formatPrixToMonetaire($montant_avec_remise, false, false)}}</p>
            </div>
            </td>
            <td>
                <div>
                    <p class="badge" style="line-height:15px">Tva({{$taxe ? $taxe["value"] : "0"}}%)</p>
                    <p style="line-height:5px">{{$montant_taxe}}</p>
                </div>
            </td>
            @if ($montant_ttc!=0)
            <td>
                <p class="badge" style="line-height:15px">Total TTC</p>
                <p style="line-height:5px">{{ \App\Models\Outil::formatPrixToMonetaire($montant_ttc, false, false)}}</p>
            </td>
            @endif
             {{-- @if (isset($montant_ttc))
             <td>
             @else
             <td colspan="2">
             @endif --}}
             <td colspan="2">
                    <p class="badge" style="font-weight: bold">Net a payer</p>
                    <p style="line-height:5px">{{ \App\Models\Outil::formatPrixToMonetaire($montant_ttc !=0 ? $montant_ttc : $montant_avec_remise, false, false)}}</p>
            </td>
        </tr>
        {{-- <tr>
            <td colspan="2"  style="padding-top : 10px;font-size: 11px">
                <p >Arretée à la somme de :</p>  
                <p style="font-weight: bold;font-size: 11px">{{\App\Models\Outil::convertNumber($montant_ttc !=0 ? $montant_ttc : $montant_avec_remise)}}</p> 
            </td>
            <td style="padding-top : 10px;font-size: 11px" colspan="2"> <p>Conditions Règlement</p> </td>
            <td style="padding-top : 10px;font-size: 11px"> <p>ESPECES</p></td>
            <td style="padding-top : 10px;font-weight: bold;font-size: 11px" colspan="2"><p> {{\App\Models\Outil::formatPrixToMonetaire($montant_ttc ? $montant_ttc : $montant_avec_remise, false, true)}} </p></td>
        </tr> --}}
        
    </tbody>
</table>
</div>

@endsection
{{-- @endif --}}