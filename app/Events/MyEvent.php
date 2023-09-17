<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\Channel;

class MyEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $vente;

  public function __construct()
  {
    //   $this->vente = $vente;
  }

  public function broadcastOn()
  {
      return new Channel('chan-demo');
  }

  public function broadcastAs()
  {
    return 'my-event';
  }

  public function broadcastWith()
  {
    return ['vente' => $this->vente];
  }
}