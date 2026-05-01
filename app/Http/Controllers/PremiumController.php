<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Factura; 
use App\Models\User;

class PremiumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->tipo_usuario, ['candidato', 'empresa'])) {
            return redirect()->route('perfil')->with('error', 'Acceso no autorizado.');
        }

        return view('premium.index', compact('user'));
    }

    public function facturacion()
    {
        $user = Auth::user();
        $facturas = Factura::where('user_id', $user->id)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('premium.facturacion', compact('user', 'facturas'));
    }

    public function cancelar()
    {
        $user = User::find(Auth::id());
        $user->update([
            'es_premium' => false,
            'premium_hasta' => null
        ]);
        return redirect()->route('premium.index')->with('success', 'Tu suscripción ha sido cancelada correctamente.');
    }
}
