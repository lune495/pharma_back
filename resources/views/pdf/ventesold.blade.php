@extends('pdf.layouts.layout-export2')
@section('title', "PDF Facture commande")
@section('content')
    <table style="border: none; margin-top:50px;font-size: 11px">
        <tr  style="border: none">
            <td  style="border: none">
                <div style="" >
                    <p  style="text-align:left;line-height:5px"> Rue SACRE COEUR 3 VDN EXTENSION </p>
                    <p style="text-align:left;line-height:5px"> +221 77 517 98 29</p>
                    <p style="text-align:left;line-height:5px"> +221 77 615 32 32</p>
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
            <td style="border: none; margin-left: 15px">
                <div>
                    <p class="badge" style="text-align:left;line-height:15px">Client</p>
                    <p style="text-align:left;margin-left:15px;line-height:5px">{{ $client ? $client["nom_complet"] : "CLIENT DE PASSAGE"}}</p>
                    <p style="text-align:left ; margin-left:15px;line-height:5px ">{{ $client ? "Téléphone: " . $client["telephone"] : " "}}</p>
                    <p style="text-align:left; margin-left:15px;line-height:5px;text-transform: capitalize "> {{ $client ? "Adresse:" . $client["adresse"] : ""}}</p>

                </div>
            </td>
        </tr>
    </table>

    <table style="border: none;font-size: 11px; margin-top:30px">
        <tr  style="border: none">
            <!-- <td style="border: none; margin-left: 15px">
                <div>
                    <p class="badge" style="text-align:left;line-height:15px">Numero</p>
                    {{-- <p style="text-align:left;line-height:5px">{{ $data[0]['code']}}</p> --}}
                    <p style="text-align:left;line-height:5px">{{$numero ? $numero : "SN0002022FA01"}}</p>
                </div>
            </td> -->
            <td style="border:none;"></td>
            <td style="border: none; margin-left: 15px">
                <div>
                    <p class="badge" style="text-align:left;line-height:15px">Date</p>
                    <p style="text-align:left;line-height:5px">{{ $created_at_fr}}</p>
                </div>
            </td>
            <td style="border:none;"></td>
        </tr>
    </table>

    <h2 style="margin:0">Facture N0  {{$numero ? $numero : "FA01"}}</h2>
    <br>
    <table class="table table-bordered w-100">
        <tr style="font-size: 1.2em;">
            <th style="border:none"> <p class="badge">Désignation</p> </th>
            <th style="border:none"><p class="badge">Qté</p></th>
            <th style="border:none"><p class="badge">P U</p></th>
            <th style="border:none"><p class="badge">Montant</p></th>
        </tr>
    <tbody style="border:none">
        @foreach($vente_produits as $vente)
            <tr style="padding:0px">
                <td style="font-size:11px;padding: 2px"> {{ \App\Models\Outil::premereLettreMajuscule($vente["produit"]["designation"])}}</td>
                <td style="font-size:11px;padding: 2px"> {{$vente["qte"]}}</td>
                <td style="font-size:11px;padding: 2px"> {{$vente["prix_vente"]}}</td>
                <td style="font-size:11px;padding: 2px">{{\App\Models\Outil::formatPrixToMonetaire($vente["qte"]*$vente["prix_vente"], false, false)}}</td>
            </tr>
        @endforeach

        <!--total-->
        <tr>
            <td colspan="1" style="border-left: 2px solid white;border-bottom: 2px solid white"></td>
            <td>
                <div>
                    <p class="badge" style="line-height:15px;font-size:9px!important">Total TTC</p>
                    <p style="line-height:5px">{{ $montant}}</p>
                </div>
            </td>
            <td>
                <div>
                    <p class="badge" style="line-height:15px">Remise</p>
                    <p style="line-height:5px">{{$remise ? $remise["value"] : "0"}}%</p>
                </div>
            </td>
            <td>
                <div>
                    <p class="badge" style="line-height:15px">tva</p>
                    <p style="line-height:5px">{{$taxe ? $taxe["value"] : "0"}}%</p>
                </div>
            </td>
            <td style="font-weight: bold;font-size: 14px"> 
                <div>
                    <p class="badge">Net a payer</p>
                    <p style="line-height:5px">{{ \App\Models\Outil::formatPrixToMonetaire($montant, false, true)}}</p>
                </div> 
            </td>
            <td style="font-weight: bold;font-size: 14px">  </td>
        </tr>
        <tr>
            <td colspan="2"  style="padding-top : 10px;font-weight: bold;font-size: 11px">Conditions Reglement</td>
            <td style="padding-top : 10px;font-weight: bold;font-size: 11px"> {{$created_at_fr}} </td>
            <td style="padding-top : 10px;font-weight: bold;font-size: 11px"> ESP</td>
            <td style="padding-top : 10px;font-weight: bold;font-size: 11px"> {{\App\Models\Outil::formatPrixToMonetaire($montant, false, true)}} </td>
        </tr>
        
    </tbody>
</table>
@endsection