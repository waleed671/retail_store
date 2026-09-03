<?php

namespace App\Http\Middleware;

use App\Services\Cart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareCartCount
{
    /**
     * Make the current cart item count available to every view (for the header badge).
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('cartCount', app(Cart::class)->itemCount());

        return $next($request);
    }
}
