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

        $deposits = $user->fundMovements()->where('type', 'deposit')->sum('amount');
        $withdraws = $user->fundMovements()->where('type', 'withdraw')->sum('amount');

        $balance = $deposits - $withdraws;

        $movements = $user->fundMovements()->orderBy('date', 'desc')->take(10)->get();

        return view('wallet.index', compact('balance', 'user', 'movements'));
    }

    public function create()
    {
        return view('wallet.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:deposit,withdraw',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'method' => 'required|in:transfer,card,paypal',
        ]);

        $user = Auth::user();
        $currentBalance = $user->balance;

        //Se hace retirada pero no hay saldo
        if ($data['type'] === 'withdraw') {
            if ($data['amount'] > $currentBalance) {
                return back()->withInput()->with('error', "Saldo insuficiente. Tu saldo disponible es de $" . number_format($currentBalance, 2));
            }
        }

        //Actualiza saldo usuario
        $user->balance += $data['type'] === 'deposit' ? $data['amount'] : -$data['amount'];
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
