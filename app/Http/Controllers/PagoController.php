<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Factura;
use Carbon\Carbon;

class PagoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('premium.index', compact('user'));
    }

    public function checkout()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => env('STRIPE_PRICE_ID'),
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('pago.exito'), 
            'cancel_url' => route('pago.cancelado'),
        ]);

        return redirect($checkout_session->url);
    }

public function exito()
{
    /** @var \App\Models\User $user */

    $user = \App\Models\User::find(Auth::id());

    if (!$user) {
        return redirect()->route('login');
    }

    $user->update([
        'es_premium'    => true,
        'premium_hasta' => \Carbon\Carbon::now()->addMonth(),
    ]);

    Factura::create([
        'user_id'    => $user->id,
        'referencia' => 'FAC-' . strtoupper(bin2hex(random_bytes(3))),
        'importe'    => 2.00,
        'concepto'   => 'Suscripción Mensual Premium',
        'estado'     => 'pagado',
    ]);
    $facturas = Factura::where('user_id', $user->id)
                       ->orderBy('created_at', 'desc')
                       ->get();

    return view('premium.exito', compact('facturas'));
}
    public function cancelado()
    {
        return redirect()->route('premium.index')->with('error', 'Has cancelado el proceso de pago.');
    }
    
    public function cancelarSuscripcion()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $user->update([
                'es_premium' => 0,
                'premium_hasta' => null
            ]);
        }

        return redirect()->route('premium.index')->with('success', 'Suscripción cancelada. Ya no eres usuario Premium.');
    }
}
