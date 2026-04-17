<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Empresa;
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

        $rules = [
            'nuevo_nombre' => 'nullable|string|max:255',
            'apellidos'    => 'nullable|string|max:255',
            'ubicacion'    => 'nullable|string|max:255',
            'habilidades_clave' => 'nullable|string',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'curriculum'   => 'nullable|mimes:pdf|max:10000'
        ];

        $request->validate($rules);

        try {
            DB::transaction(function () use ($request, $user) {
                $datosUpdate = [];

                // Buscamos el perfil actual para saber qué archivos borrar
                $perfilActual = ($user->tipo_usuario == 'candidato')
                    ? Candidato::where('id_usuario', $user->id)->first()
                    : Empresa::where('id_usuario', $user->id)->first();

                if ($request->filled('ubicacion')) $datosUpdate['ubicacion'] = $request->ubicacion;
                if ($request->filled('apellidos')) $datosUpdate['apellidos'] = $request->apellidos;
                if ($request->filled('habilidades_clave')) $datosUpdate['habilidades_clave'] = $request->habilidades_clave;

                // --- GESTIÓN DE FOTO (CON BORRADO) ---
                if ($request->hasFile('foto')) {
                    if ($perfilActual && $perfilActual->foto) {
                        $rutaAnterior = public_path('uploads/perfiles/' . $perfilActual->foto);
                        if (File::exists($rutaAnterior)) {
                            File::delete($rutaAnterior);
                        }
                    }

                    // 2. Guardar la nueva
                    $foto = $request->file('foto');
                    $nombreFoto = time() . "_perfil_" . $user->id . "." . $foto->getClientOriginalExtension();
                    $foto->move(public_path('uploads/perfiles'), $nombreFoto);
                    $datosUpdate['foto'] = $nombreFoto;
                }

                // --- GESTIÓN DE CURRÍCULUM (CON BORRADO) ---
                if ($request->hasFile('curriculum')) {
                    // 1. Borrar el PDF antiguo si existe
                    if ($perfilActual && $perfilActual->curriculum) {
                        $rutaCVAnterior = public_path('uploads/curriculums/' . $perfilActual->curriculum);
                        if (File::exists($rutaCVAnterior)) {
                            File::delete($rutaCVAnterior);
                        }
                    }

                    // 2. Guardar el nuevo
                    $file = $request->file('curriculum');
                    $nombre_cv = time() . "_cv_" . $user->id . ".pdf";
                    $file->move(public_path('uploads/curriculums'), $nombre_cv);
                    $datosUpdate['curriculum'] = $nombre_cv;
                }

                if ($user->tipo_usuario == 'candidato') {
                    if ($request->filled('nuevo_nombre')) {
                        $datosUpdate['nombre'] = $request->nuevo_nombre;
                        session(['nombre' => $request->nuevo_nombre]);
                    }
                    Candidato::where('id_usuario', $user->id)->update($datosUpdate);
                } else {
                    if ($request->filled('nuevo_nombre')) {
                        $datosUpdate['nombre_empresa'] = $request->nuevo_nombre;
                        session(['nombre' => $request->nuevo_nombre]);
                    }
                    if ($request->filled('sector')) $datosUpdate['sector'] = $request->sector;

                    Empresa::where('id_usuario', $user->id)->update($datosUpdate);
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
}
