<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    public function index()
    {
        $id_usuario = Auth::id();

        $favoritos = DB::table('favoritos as f')
            ->join('ofertas as o', 'f.id_oferta', '=', 'o.id')
            ->join('empresas as e', 'o.id_empresa', '=', 'e.id_empresa')
            ->join('categorias as c', 'o.id_categoria', '=', 'c.id_categoria')
            ->where('f.id_usuario', $id_usuario)
            ->select('o.*', 'e.nombre_empresa', 'c.nombre_categoria')
            // Cambiado a created_at o la fecha que uses, por si acaso
            ->orderBy('f.id', 'DESC') 
            ->get();

        return view('candidato.favoritos', compact('favoritos'));
    }

    public function toggle($id_oferta)
    {
        $id_usuario = Auth::id();
        if (!$id_usuario) return redirect()->route('login');

        $existe = DB::table('favoritos')
            ->where('id_usuario', $id_usuario)
            ->where('id_oferta', $id_oferta)
            ->first();

        if ($existe) {
            // Borrado seguro por combinación de claves
            DB::table('favoritos')
                ->where('id_usuario', $id_usuario)
                ->where('id_oferta', $id_oferta)
                ->delete();
                
            return back()->with('fav_ok', 'Eliminado de favoritos');
        } else {
            DB::table('favoritos')->insert([
                'id_usuario' => $id_usuario,
                'id_oferta'  => $id_oferta,
                'fecha_guardado' => now() 
            ]);
            
            return back()->with('fav_ok', 'Añadido a favoritos');
        }
    }
}
