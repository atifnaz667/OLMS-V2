<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\UserActivity;

class UpdateLastActivity
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(UserActivity $event)
  {
    $user = $event->user;
    $timestamp = $event->timestamp;

    // Update the last_activity_at timestamp
    $user->last_activity_at = $timestamp;
    $user->save();
  }
}
