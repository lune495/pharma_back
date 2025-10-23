<?php

namespace Database\Seeders;

use  Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Role,Taxe,LigneApprovisionnement};


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $taxes = array();
        // array_push($taxes,array("value" => "18"));
        // $newtaxe = Taxe::where('value', $taxes[0]['value'])->first();
        // if (!isset($newtaxe))
        // {
        //     $newtaxe = new Taxe();
        //     $newtaxe->value = $taxes[0]['value'];
        // }
        // $newtaxe->save();
        // $newrole = Role::where('nom','ADMIN')->first();
        // if(!isset($newrole))
        // {
        //       DB::table('roles')->insert([
        //         'nom'=>'ADMIN',
        //       ]);
        // } 
        
        $ligne_appros = LigneApprovisionnement::all();
        foreach($ligne_appros as $ligne_appro)
        {
              $ligne_appro->pu = $ligne_appro->produit->pa;
              $ligne_appro->save();
        } 
    }
}
