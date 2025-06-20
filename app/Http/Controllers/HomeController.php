<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        return view('layouts.home', [
            'topCryptos' => $coins,
        ]);
    }
}
