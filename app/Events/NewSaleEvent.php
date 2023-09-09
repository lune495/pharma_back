<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSaleEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $sale;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sale)
    {
        //
        $this->sale = $sale;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('ventes');
    }
    
    /**
     * Les données à diffuser avec l'événement.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Vous pouvez personnaliser les données que vous souhaitez diffuser avec l'événement.
        // Par exemple, vous pouvez inclure des détails sur la vente nouvellement enregistrée.
        return [
            'message' => 'Nouvelle vente enregistrée!',
            'sale' => $this->sale,
        ];
    }
}
