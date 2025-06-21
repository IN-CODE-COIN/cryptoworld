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

        //* Si el usuario no es pro y ha alcanzado el límite de 5 criptomonedas en su watchlist *//
        if ($count >= 5 && !$user->isPro() && !$user->onTrial()) {
            return redirect()->back()->with('warning', 'Has alcanzado el límite de criptomonedas en tu lista de seguimiento. Actualiza tu cuenta a premium para aumentar tu límite.');
        }

        //* Limpiar la lista de watchlist y dejar solo 5 *//
        if (!$user->isPro() && !$user->onTrial() && $user->watchlist()->count() > 5) {
            $user->watchlist()
                ->orderBy('created_at', 'desc')
                ->skip(5)
                ->take(PHP_INT_MAX)
                ->get()
                ->each
                ->delete();
        }

        $exists = $user->watchlist()
            ->where('coin_uuid', $request->uuid)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('warning', 'Esta criptomoneda ya está en tu lista de seguimiento.');
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

        return redirect()->back()->with('success', 'Cripto añadida a tu lista de seguimiento.');
    }

    public function destroy(Watchlist $watchlist)
    {
        $this->authorize('delete', $watchlist);

        $watchlist->delete();

        return redirect()->back()->with('success', 'Cripto eliminada de la lista de seguimiento.');
    }
}

