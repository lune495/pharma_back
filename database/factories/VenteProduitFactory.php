<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class VenteProduitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'produit_id'        => 120,
            'vente_id'          => 50,
            'qte'               => 200,
            'prix_vente'        => 50000,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }
}
