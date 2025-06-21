<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PricingController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('pricing.index', [
            'user' => $user,
            'onTrial' => $user?->onTrial(),
            'isPro' => $user?->isPro(),
        ]);
    }

    public function startTrial(Request $request)
    {
        $user = Auth::user();

        if ($user->trial_ends_at || $user->is_pro) {
            return back()->with('warning', 'Ya estás en un plan Pro o ya usaste tu prueba.');
        }

        $user->trial_ends_at = Carbon::now()->addDays(7);
        $user->save();

        return back()->with('success', 'Tu prueba gratuita ha comenzado y durará 7 días.');
    }
}
