<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

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

        $watchlistUuids = Auth::user()?->watchlist()->pluck('coin_uuid')->toArray();

        return view('layouts.show', [
            'coin' => $coin,
            'watchlistUuids' => $watchlistUuids
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

    public function getPrice(Request $request)
    {
        $uuid = $request->query('uuid');
        $timestamp = $request->query('timestamp');

        if (!$uuid || !$timestamp) {
            return response()->json([
                'status' => 'error',
                'message' => 'Faltan parámetros uuid o timestamp'
            ], 400);
        }

        $apiKey = env('COINRANKING_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'API key no configurada'
            ], 500);
        }

        $url = "https://api.coinranking.com/v2/coin/{$uuid}/price";

        $response = Http::withHeaders([
            'x-access-token' => $apiKey,
        ])->get($url, [
            'timestamp' => $timestamp,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en Coinranking',
                'body' => $response->body()
            ], $response->status());
        }

        return $response->json();
    }

}
