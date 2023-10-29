<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LastUserActivity
{
  public function handle($request, Closure $next)
  {
      if (Auth::check()) {
          $expiresAt = Carbon::now()->addMinutes(5); // keep online for 5 min
          Cache::put('user-is-online-' . Auth::user()->id, true, $expiresAt);

          // last seen
          User::where('id', Auth::user()->id)->update(['last_seen' => (new \DateTime())->format("Y-m-d H:i:s")]);
      }

      return $next($request);
  }
}
