<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CryptoPosition;
use App\Models\CryptoTransaction;
use App\Models\FundMovement;

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
        $fees = $data['fees'] ?? 0;
        $grossTotal = $data['quantity'] * $data['price_usd'];
        $netTotal = $grossTotal - $fees;
        $totalCost = $grossTotal + $fees;

        $position = CryptoPosition::firstOrNew([
            'user_id' => $user->id,
            'crypto_id' => $data['crypto_id'],
        ]);
        $position->crypto_name = $data['crypto_name'];

        if ($data['type'] === 'buy') {
            $user = Auth::user();
            // Verifica saldo
            if ($user->balance < $totalCost) {
                return back()->withErrors(['quantity' => 'No tienes saldo suficiente para esta compra.'])->withInput();
            }

            // Actualiza saldo
            $user->balance -= $totalCost;
            $user->save();

            // Actualiza posición
            $newAmount = $position->amount + $data['quantity'];
            $newAveragePrice = $position->amount == 0
                ? $data['price_usd']
                : (($position->average_price * $position->amount) + ($data['price_usd'] * $data['quantity'])) / $newAmount;

            $position->amount = $newAmount;
            $position->average_price = $newAveragePrice;

            // Movimiento por compra
            FundMovement::create([
                'user_id' => $user->id,
                'type' => 'retirada',
                'amount' => -$grossTotal,
                'method' => 'transfer',
                'description' => "Compra de {$data['quantity']} {$data['crypto_name']} a \${$data['price_usd']} USD",
                'date' => $data['date'],
            ]);

        } else {
            // Venta
            $user = Auth::user();
            if ($position->amount < $data['quantity']) {
                return back()->withErrors(['quantity' => 'No tienes suficientes criptomonedas para esta venta.'])->withInput();
            }

            $position->amount -= $data['quantity'];

            // Actualiza saldo
            $user->balance += $netTotal;
            $user->save();

            // Movimiento por venta
            FundMovement::create([
                'user_id' => $user->id,
                'type' => 'deposito',
                'amount' => $grossTotal,
                'method' => 'transfer',
                'description' => "Venta de {$data['quantity']} {$data['crypto_name']} a \${$data['price_usd']} USD",
                'date' => $data['date'],
            ]);
        }

        $position->save();

        // Movimiento por fees (compra o venta)
        if ($fees > 0) {
            FundMovement::create([
                'user_id' => $user->id,
                'type' => 'retirada',
                'amount' => -$fees,
                'method' => 'transfer',
                'description' => "Comisión por {$data['type']} de {$data['crypto_name']}",
                'date' => $data['date'],
            ]);
        }

        // Guarda transacción
        CryptoTransaction::create([
            'user_id' => $user->id,
            'crypto_position_id' => $position->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'amount_usd' => $data['amount_usd'],
            'price_usd' => $data['price_usd'],
            'fees' => $fees,
            'total_cost' => $data['type'] === 'buy' ? $totalCost : $grossTotal,
            'date' => $data['date'],
        ]);

        return redirect()->route('wallet.index')->with('success', 'Transacción registrada correctamente.');
    }

}
