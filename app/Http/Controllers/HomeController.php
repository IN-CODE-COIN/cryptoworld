<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $response = Http::withHeaders([
            'x-access-token' => env('COINRANKING_API_KEY'),
        ])->get('https://api.coinranking.com/v2/coins', [
            'limit' => 10,
        ]);

        $coins = $response->json()['data']['coins'] ?? [];

        $watchlistUuids = Auth::user()?->watchlist()->pluck('coin_uuid')->toArray();

        return view('layouts.home', [
            'topCryptos' => $coins,
            'watchlistUuids' => $watchlistUuids,
        ]);
    }

}
