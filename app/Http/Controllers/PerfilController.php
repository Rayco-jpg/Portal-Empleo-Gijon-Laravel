<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Empresa;
use App\Models\Oferta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PerfilController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $perfil = ($user->tipo_usuario == 'candidato')
            ? Candidato::where('id_usuario', $user->id)->first()
            : Empresa::where('id_usuario', $user->id)->first();

        $categorias = DB::table('categorias')->orderBy('nombre_categoria', 'ASC')->get();
        $alerta_actual = DB::table('alertas')->where('id_usuario', $user->id)->value('id_categoria');

        return view('perfil', compact('user', 'perfil', 'categorias', 'alerta_actual'));
    }

    public function edit()
    {
        $user = Auth::user();
        $perfil = ($user->tipo_usuario == 'candidato')
            ? Candidato::where('id_usuario', $user->id)->first()
            : Empresa::where('id_usuario', $user->id)->first();

        return view('editar_perfil', compact('user', 'perfil'));
    }

    public function updatePerfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'nuevo_nombre' => 'nullable|string|max:255',
            'apellidos'    => 'nullable|string|max:255',
            'ubicacion'    => 'nullable|string|max:255',
            'sector'       => 'nullable|string|max:255',
            'tamano'       => 'nullable|string|max:255',
            'sitio_web'    => 'nullable|string|max:255',
            'descripcion'  => 'nullable|string',
            'twitter'      => 'nullable|string|max:255',
            'facebook'  => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'whatsapp'  => 'nullable|string|max:255',
            'biografia'    => 'nullable|string',
            'habilidades_clave' => 'nullable|string',
            'disponible'   => 'nullable|integer|in:0,1',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'curriculum'   => 'nullable|mimes:pdf|max:10000'
        ]);

        try {
            DB::transaction(function () use ($request, $user) {
                $datosUpdate = [];

                $perfilActual = ($user->tipo_usuario == 'candidato')
                    ? Candidato::where('id_usuario', $user->id)->first()
                    : Empresa::where('id_usuario', $user->id)->first();

                if ($request->hasFile('foto')) {
                    if ($perfilActual && $perfilActual->foto) {
                        $ruta = public_path('uploads/perfiles/' . $perfilActual->foto);
                        if (File::exists($ruta)) {
                            File::delete($ruta);
                        }
                    }
                    $foto = $request->file('foto');
                    $nombreFoto = time() . "_perfil_" . $user->id . "." . $foto->getClientOriginalExtension();
                    $foto->move(public_path('uploads/perfiles'), $nombreFoto);

                    $datosUpdate['foto'] = $nombreFoto;
                }

                if ($request->hasFile('curriculum') && $user->tipo_usuario == 'candidato') {
                    if ($perfilActual && $perfilActual->curriculum) {
                        $rutaCV = public_path('uploads/curriculums/' . $perfilActual->curriculum);
                        if (File::exists($rutaCV)) {
                            File::delete($rutaCV);
                        }
                    }
                    $nombre_cv = time() . "_cv_" . $user->id . ".pdf";
                    $request->file('curriculum')->move(public_path('uploads/curriculums'), $nombre_cv);
                    $datosUpdate['curriculum'] = $nombre_cv;
                }

                if ($user->tipo_usuario == 'candidato') {
                    if ($request->filled('nuevo_nombre')) $datosUpdate['nombre'] = $request->nuevo_nombre;
                    if ($request->filled('apellidos')) $datosUpdate['apellidos'] = $request->apellidos;
                    if ($request->filled('ubicacion')) $datosUpdate['ubicacion'] = $request->ubicacion;
                    if ($request->has('biografia')) $datosUpdate['biografia'] = $request->biografia;
                    if ($request->filled('habilidades_clave')) $datosUpdate['habilidades_clave'] = $request->habilidades_clave;
                    if ($request->has('disponible')) $datosUpdate['disponible'] = $request->disponible;

                    Candidato::where('id_usuario', $user->id)->update($datosUpdate);
                } else {
                    if ($request->filled('nuevo_nombre')) $datosUpdate['nombre_empresa'] = $request->nuevo_nombre;
                    if ($request->filled('ubicacion')) $datosUpdate['ubicacion'] = $request->ubicacion;
                    if ($request->filled('sector')) $datosUpdate['sector'] = $request->sector;
                    if ($request->filled('tamano')) $datosUpdate['tamano'] = $request->tamano;
                    if ($request->filled('sitio_web')) $datosUpdate['sitio_web'] = $request->sitio_web;
                    if ($request->filled('twitter')) $datosUpdate['twitter'] = $request->twitter;
                    if ($request->filled('facebook')) $datosUpdate['facebook'] = $request->facebook;
                    if ($request->filled('instagram')) $datosUpdate['instagram'] = $request->instagram;
                    if ($request->filled('whatsapp')) $datosUpdate['whatsapp'] = $request->whatsapp;
                    if ($request->has('descripcion')) {
                        $datosUpdate['descripcion'] = $request->descripcion;
                    }

                    Empresa::where('id_usuario', $user->id)->update($datosUpdate);
                }

                if ($request->filled('nuevo_nombre')) {
                    session(['nombre' => $request->nuevo_nombre]);
                }
            });

            return redirect()->route('perfil')->with('success', '¡Perfil actualizado correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar: ' . $e->getMessage());
        }
    }

    public function guardarAlerta(Request $request)
    {
        $id_usuario = Auth::id();
        $id_cat = $request->id_categoria;

        if (empty($id_cat)) {
            DB::table('alertas')->where('id_usuario', $id_usuario)->delete();
        } else {
            DB::table('alertas')->updateOrInsert(
                ['id_usuario' => $id_usuario],
                ['id_categoria' => $id_cat]
            );
        }
        return back()->with('success', 'Configuración de alertas actualizada.');
    }

    public function verPerfilPublico($id)
    {
        $usuario = User::findOrFail($id);
        $perfil = Candidato::where('id_usuario', $id)->first();

        if (Auth::check() && Auth::user()->tipo_usuario === 'empresa') {
            DB::table('visitas_perfil')->insert([
                'id_candidato' => $id,
                'id_empresa'   => Auth::id(),
                'fecha_visita' => now()
            ]);
        }
        return view('perfil_publico_candidato', compact('usuario', 'perfil'));
    }

    public function verPerfilEmpresa($id)
    {
        $empresa = \App\Models\Empresa::where('id_usuario', $id)->firstOrFail();
        $ofertas = \App\Models\Oferta::where('id_empresa', $empresa->id_empresa)->get();
        return view('perfil_publico_empresa', compact('empresa', 'ofertas'));
    }
}
