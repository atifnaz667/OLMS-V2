<?php

namespace App\Http\Middleware;

use Closure;
use App\Events\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class ParentMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
   * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
   */
  public function handle(Request $request, Closure $next)
  {
    if (!Auth::user() || Auth::user()->role_id != 2) {
      return redirect('/');
    } elseif (Auth::user()->status == 'pending') {
      return redirect('pending-user');
    }
    $user = auth()->user();
    $timestamp = now();
    Event::dispatch(new UserActivity($user, $timestamp));

    return $next($request);
  }
}
