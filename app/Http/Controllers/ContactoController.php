<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:50',
            'mensaje' => 'required|string|max:1000',
        ]);

        Contacto::create([
            'user_id' => Auth::id(),
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Reporte de oferta recibido correctamente.'
            ]);
        }

        return redirect()->back()->with('success', '¡Gracias! Tu reporte ha sido enviado al equipo técnico.');
    }

    public function index()
    {
        $mensajes = Contacto::with('user')->latest()->get();

        return view('admin.mensajes', compact('mensajes'));
    }

    public function marcarLeido($id)
    {
        $mensaje = Contacto::findOrFail($id);
        
        $mensaje->update([
            'leido' => true
        ]);

        return redirect()->back()->with('success', 'Mensaje marcado como revisado.');
    }
}