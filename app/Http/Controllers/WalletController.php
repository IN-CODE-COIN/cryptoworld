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

        $movements = $user->fundMovements()->orderBy('date', 'desc')->take(10)->get();

        $positions = [
            (object)[
                'symbol' => 'BTC',
                'quantity' => 0.5,
                'profit' => 320.45,
                'daily_change' => 1.82,
                'total_change' => 12.45
            ],
            (object)[
                'symbol' => 'ETH',
                'quantity' => 1.8,
                'profit' => -75.20,
                'daily_change' => -0.9,
                'total_change' => -3.45
            ],
        ];

        return view('wallet.index', compact('balance', 'user', 'movements', 'positions'));
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
            'type' => $data['type'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'method' => $data['method'],
            'date' => now(),
        ]);

        return redirect()->route('wallet.index')->with('success', 'Movimiento registrado correctamente.');
    }

}
