<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Vista principal con estadísticas
    public function index()
    {
        $totalUsuarios = User::count();
        $totalOfertas = Oferta::count();

        // AÑADE ESTA LÍNEA para que la tabla funcione
        $usuarios = User::all();

        // Envía también 'usuarios' a la vista
        return view('admin.index', compact('totalUsuarios', 'totalOfertas', 'usuarios'));
    }

    // Listar todos los usuarios
    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    // Borrar un usuario (Candidato o Empresa)
    public function destroyUsuario($id)
    {
        // 1. Buscamos al usuario
        $usuario = \App\Models\User::findOrFail($id);

        // 2. Seguridad: Evitar que el admin se borre a sí mismo
        if ($usuario->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes borrar tu propia cuenta de administrador.');
        }

        // 3. ¡A borrar!
        $usuario->delete();

        // 4. Volvemos atrás con un mensaje de éxito
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    // Listar todas las ofertas de todas las empresas
    public function ofertas()
    {
        // 'with' carga los datos del usuario de golpe para que aparezca el nombre
        $ofertas = Oferta::with('user')->get();
        return view('admin.ofertas', compact('ofertas'));
    }
    // Borrar una oferta "mala" o inapropiada
    public function destroyOferta($id)
    {
        $oferta = Oferta::findOrFail($id);
        $oferta->delete();

        return redirect()->back()->with('success', 'La oferta ha sido eliminada por el administrador.');
    }
}
