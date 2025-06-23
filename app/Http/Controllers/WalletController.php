<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FundMovement;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $deposits = $user->fundMovements()->where('type', 'deposito')->sum('amount');
        $withdraws = $user->fundMovements()->where('type', 'retirada')->sum('amount');
        $balance = $deposits - $withdraws;

        $movements = $user->fundMovements()->orderBy('date', 'desc')->take(5)->get();

        $positions = $user->cryptoPositions()
            ->where('amount', '>', 0)
            ->get()
            ->map(function ($pos) {
                $coinUuid = $pos->crypto_id;
                $cryptoName = $pos->crypto_name;

                // Llama a la API de CoinRanking
                $response = \Http::withHeaders([
                    'x-access-token' => env('COINRANKING_API_KEY')
                ])->get("https://api.coinranking.com/v2/coin/{$coinUuid}");

                $data = $response->json();

                $currentPrice = isset($data['data']['coin']['price']) ? floatval($data['data']['coin']['price']) : 0;
                $currentValue = $currentPrice * $pos->amount;
                $profit = $currentValue - $pos->invested_usd;
                $change = $pos->invested_usd > 0 ? ($profit / $pos->invested_usd) * 100 : 0;

                return (object) [
                    'symbol' => strtoupper($cryptoName),
                    'amount' => $pos->amount,
                    'quantity' => $pos->invested_usd,
                    'average_price' => $pos->average_price,
                    'current_price' => $currentPrice,
                    'profit' => $profit,
                    'total_change' => $change,
                ];
            });

        $totalInvested = $positions->sum('quantity');
        $totalCurrent = $positions->sum(function ($pos) {
            return $pos->current_price * $pos->amount;
        });

        $totalProfit = $totalCurrent - $totalInvested;
        $totalChange = $totalInvested > 0 ? ($totalProfit / $totalInvested) * 100 : 0;

        return view('wallet.index', compact('balance', 'user', 'movements', 'positions', 'totalProfit', 'totalChange'));
    }


    public function create()
    {
        $user = Auth::user();
        return view('wallet.create', ['balance' => $user->balance]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:deposito,retirada',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'method' => 'required|in:transfer,card,paypal',
        ]);

        $user = Auth::user();
        $currentBalance = $user->balance;

        //Se hace retirada pero no hay saldo
        if ($data['type'] === 'retirada') {
            if ($data['amount'] > $currentBalance) {
                return back()->withInput()->with('error', "Saldo insuficiente. Tu saldo disponible es de $" . number_format($currentBalance, 2));
            }
        }

        //Actualiza saldo usuario
        $user->balance += $data['type'] === 'deposito' ? $data['amount'] : -$data['amount'];
        $user->save();

        //Registro movimiento
        $user->fundMovements()->create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'method' => $data['method'],
            'date' => now(),
        ]);

        return redirect()->route('wallet.index')->with('success', 'Movimiento registrado correctamente.');
    }

    public function show()
    {
        $user = Auth::user();

        $movements = $user->fundMovements()->orderBy('date', 'desc')->get();

        return view('wallet.moves', compact('movements'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $movement = $user->fundMovements()->findOrFail($id);

        return view('wallet.edit', compact('movement'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $movement = $user->fundMovements()->findOrFail($id);
        $movement->description = $data['description'];
        $movement->save();

        return redirect()->route('wallet.moves')->with('success', 'Descripción actualizada correctamente.');
    }

}
