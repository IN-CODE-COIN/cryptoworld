<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CryptoPosition;
use App\Models\CryptoTransaction;

class CryptoTransactionController extends Controller
{
    public function create()
    {
        return view('wallet.transaction.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'crypto_id' => 'required|string',
            'crypto_name' => 'required|string',
            'type' => 'required|in:buy,sell',
            'date' => 'required|date',
            'amount_usd' => 'required|numeric|min:0.01',
            'price_usd' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:0.00000001',
            'fees' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();

        $totalCost = ($data['quantity'] * $data['price_usd']) + ($data['fees'] ?? 0);

        if ($data['type'] === 'buy' && $user->balance < $totalCost) {
            return back()->withErrors(['quantity' => 'No tienes saldo suficiente para esta compra.'])
                         ->withInput();
        }

        $position = CryptoPosition::firstOrNew([
            'user_id' => $user->id,
            'crypto_id' => $data['crypto_id'],
        ]);
        $position->crypto_name = $data['crypto_name'];

        if ($data['type'] === 'buy') {

            $newAmount = $position->amount + $data['quantity'];
            $newAveragePrice = $position->amount == 0
                ? $data['price_usd']
                : (($position->average_price * $position->amount) + ($data['price_usd'] * $data['quantity'])) / $newAmount;
            $position->amount = $newAmount;
            $position->average_price = $newAveragePrice;

            $user->balance -= $totalCost;
            $user->save();

        } else {
            if ($position->amount < $data['quantity']) {
                return back()->withErrors(['quantity' => 'No tienes suficientes criptomonedas para esta venta.'])
                             ->withInput();
            }
            $position->amount -= $data['quantity'];

            $user->balance += ($data['quantity'] * $data['price_usd']) - ($data['fees'] ?? 0);
            $user->save();
        }

        $position->save();

        CryptoTransaction::create([
            'user_id' => $user->id,
            'crypto_position_id' => $position->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'amount_usd' => $data['amount_usd'],
            'price_usd' => $data['price_usd'],
            'fees' => $data['fees'] ?? 0,
            'total_cost' => $totalCost,
            'date' => $data['date'],
        ]);

        return redirect()->route('wallet.index')->with('success', 'Transacción registrada correctamente.');
    }
}
