<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserActivity
{
  use Dispatchable, InteractsWithSockets, SerializesModels;



  public $user;
  public $timestamp;

  public function __construct(User $user, $timestamp)
  {
    $this->user = $user;
    $this->timestamp = $timestamp;
    $user->last_activity_at = $timestamp;
    $user->save();
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array
  {
    return [
      new PrivateChannel('channel-name'),
    ];
  }
}
