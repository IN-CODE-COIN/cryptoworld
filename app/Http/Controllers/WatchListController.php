<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watchlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class WatchlistController extends Controller
{

    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index()
    {
        $watchlist = Auth::user()->watchlist;

        return view('watchlist.index', compact('watchlist'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $count = $user->watchlist()->count();

        if ($count >= 5 && !$user->isPro()) {
            return redirect()->back()->with('warning', 'Has alcanzado el límite de criptomonedas en tu watchlist. Actualiza tu cuenta a premium para aumentar tu límite.');
        }

        $exists = $user->watchlist()
            ->where('coin_uuid', $request->uuid)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Esta criptomoneda ya está en tu watchlist.');
        }

        $watchlist = new Watchlist([
            'coin_uuid' => $request->uuid,
            'name' => $request->name,
            'symbol' => $request->symbol,
            'icon_url' => $request->iconUrl,
            'price' => $request->price,
            'change' => $request->change,
            'market_cap' => $request->marketCap,
        ]);

        Auth::user()->watchlist()->save($watchlist);

        return redirect()->back()->with('success', 'Cripto añadida a tu watchlist.');
    }

    public function destroy(Watchlist $watchlist)
    {
        $this->authorize('delete', $watchlist);

        $watchlist->delete();

        return redirect()->back()->with('success', 'Cripto eliminada.');
    }
}

