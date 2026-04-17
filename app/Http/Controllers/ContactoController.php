<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    /**
     * Guarda el reporte enviado por el usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asunto' => 'required|string|max:50',
            'mensaje' => 'required|string|min:10|max:1000',
        ]);

        Contacto::create([
            'user_id' => Auth::id(),
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
        ]);

        return redirect()->back()->with('success', '¡Gracias! Tu reporte ha sido enviado al equipo técnico.');
    }

    /**
     * Muestra la lista de reportes solo al administrador.
     */
    public function index()
    {
        // Traemos los mensajes con su usuario, ordenados por los más recientes
        $mensajes = Contacto::with('user')->latest()->get();

        return view('admin.mensajes', compact('mensajes'));
    }

    /**
     * Marca un mensaje como leído/revisado.
     */
    public function marcarLeido($id)
    {
        // Buscamos el mensaje o lanzamos error 404 si no existe
        $mensaje = Contacto::findOrFail($id);
        
        // Actualizamos el estado
        $mensaje->update([
            'leido' => true
        ]);

        return redirect()->back()->with('success', 'Mensaje marcado como revisado.');
    }
}