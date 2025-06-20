<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CryptoController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $response = Http::withHeaders([
            'x-access-token' => env('COINRANKING_API_KEY')])
            ->get('https://api.coinranking.com/v2/coins', [
                'search' => $query,
                'limit' => 1
            ]);

        $coin = collect($response->json()['data']['coins'])->first();

        return redirect()->route('crypto.show', ['uuid' => $coin['uuid']]);
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

    public function autocomplete(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $response = Http::withHeaders([
            'x-access-token' => env('COINRANKING_API_KEY'),
        ])->get('https://api.coinranking.com/v2/coins', [
            'search' => $query,
            'limit' => 5,
        ]);

        $coins = $response->json()['data']['coins'];

        $results = array_map(function ($coin) {
            return [
                'uuid' => $coin['uuid'],
                'name' => $coin['name'],
                'symbol' => $coin['symbol'],
                'iconUrl' => $coin['iconUrl'],
            ];
        }, $coins);

        return response()->json($results);
    }


}
