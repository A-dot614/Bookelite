<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SellerCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->with('error', 'Please sign in to access the seller studio.');
        }

        $user = Auth::user();
        $seller = $user->seller;

        if (! $seller) {
            return redirect()->route('seller.register')->with('status', 'Please register your merchant profile to access the seller studio.');
        }

        if ($seller->isPending()) {
            return redirect()->route('seller.register')->with('error', 'Your store application is awaiting review. You will be able to manage your inventory once it is approved.');
        }

        if ($seller->isRejected()) {
            $reason = $seller->rejection_reason ? 'Reason: '.$seller->rejection_reason : '';
            return redirect()->route('seller.register')->with('error', 'Your store application was not approved. '.$reason);
        }

        if (! $seller->isApproved()) {
            return redirect()->route('seller.register')->with('error', 'Your store is currently unable to list products. Please contact support.');
        }

        return $next($request);
    }
}