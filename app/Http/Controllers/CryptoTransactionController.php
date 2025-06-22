<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CryptoTransactionController extends Controller
{
    public function create()
    {
        $balance = auth()->user()->balance;
        return view('wallet.transaction.create', compact('balance'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'coin_uuid'    => 'required|string',
            'symbol'       => 'required|string',
            'price_usd'    => 'required|numeric',
            'amount_coin'  => 'required|numeric',
            'total_usd'    => 'required|numeric',
            'fees'         => 'nullable|numeric',
            'type'         => 'required|in:buy,sell',
            'date'         => 'required|date',
        ]);

        $user = auth()->user();

        $totalWithFees = $data['total_usd'] + ($data['fees'] ?? 0);

        if ($data['type'] === 'buy' && $user->balance < $totalWithFees) {
            return back()->withInput()->with('error', 'Saldo insuficiente. Máximo disponible: $' . number_format($user->balance, 2));
        }

        $tx = $user->cryptoTransactions()->create($data);

        if ($data['type'] === 'buy') {
            $user->balance -= $totalWithFees;
            $user->save();
        } elseif ($data['type'] === 'sell') {
            $user->balance += ($data['total_usd'] - ($data['fees'] ?? 0));
            $user->save();
        }

        return redirect()->route('wallet.index')->with('success', 'Transacción registrada con éxito.');
    }
}
