<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Oferta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsuarios = User::count();
        $totalOfertas = Oferta::count();
        $usuarios = User::all();
        return view('admin.index', compact('totalUsuarios', 'totalOfertas', 'usuarios'));
    }

    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    public function destroyUsuario($id)
    {
        $usuario = \App\Models\User::findOrFail($id);
        if ($usuario->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes borrar tu propia cuenta de administrador.');
        }
        $usuario->delete();
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    public function ofertas()
    {
        $ofertas = Oferta::with('user')->get();
        return view('admin.ofertas', compact('ofertas'));
    }
    
    public function destroyOferta($id)
    {
        $oferta = Oferta::findOrFail($id);
        $oferta->delete();

        return redirect()->back()->with('success', 'La oferta ha sido eliminada por el administrador.');
    }
}
