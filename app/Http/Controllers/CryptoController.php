<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $response = Http::Http::withHeaders([
            'x-access-token' => env('COINRANKING_API_KEY')])
            ->get('https://api.coinranking.com/v2/coins?search=' . $query);

        $coin = collect($response->json()['data']['coins'])->first();

        $topCoins = Http::get('https://api.coinranking.com/v2/coins', ['limit' => 10])
            ->json()['data']['coins'];

        return view('layouts.home', [
            'crypto' => $coin,
            'topCryptos' => $topCoins,
        ]);
    }

    public function show($uuid)
    {
        $response = Http::withHeaders([
            'x-access-token' => env('COINRANKING_API_KEY')
        ])->get("https://api.coinranking.com/v2/coin/{$uuid}");

        $coin = $response->json()['data']['coin'];

        return view('layouts.show', [
            'coin' => $coin,
        ]);
    }

}
