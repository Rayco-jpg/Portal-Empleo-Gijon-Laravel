<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    /**
     * Guarda el reporte enviado por el usuario en la base de datos.
     * Soporta tanto envíos de formulario normales como peticiones AJAX (banderita).
     */
    public function store(Request $request)
    {
        // Validamos los datos. 
        // Nota: El 'asunto' es obligatorio para que tu panel lo clasifique bien.
        $request->validate([
            'asunto' => 'required|string|max:50',
            'mensaje' => 'required|string|max:1000',
        ]);

        // Creamos el registro en la tabla 'contactos'
        Contacto::create([
            'user_id' => Auth::id(),
            'asunto' => $request->asunto,
            'mensaje' => $request->mensaje,
        ]);

        // Si la petición viene del JavaScript (fetch/banderita), devolvemos JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Reporte de oferta recibido correctamente.'
            ]);
        }

        // Si viene de un formulario normal, redirigimos atrás con mensaje de éxito
        return redirect()->back()->with('success', '¡Gracias! Tu reporte ha sido enviado al equipo técnico.');
    }

    /**
     * Muestra la lista de reportes solo al administrador.
     */
    public function index()
    {
        $mensajes = Contacto::with('user')->latest()->get();

        return view('admin.mensajes', compact('mensajes'));
    }

    /**
     * Marca un mensaje como leído/revisado.
     */
    public function marcarLeido($id)
    {
        $mensaje = Contacto::findOrFail($id);
        
        $mensaje->update([
            'leido' => true
        ]);

        return redirect()->back()->with('success', 'Mensaje marcado como revisado.');
    }
}