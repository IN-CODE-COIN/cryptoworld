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

        // Actualizamos saldo
        if ($data['type'] === 'withdraw' && $user->balance < $data['amount']) {
            return back()->with('error', 'No tienes saldo suficiente.');
        }

        $user->balance += $data['type'] === 'deposit' ? $data['amount'] : -$data['amount'];
        $user->save();

        // Guardamos movimiento
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
