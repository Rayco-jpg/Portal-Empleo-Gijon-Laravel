<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Inscripcion;
use App\Models\Candidato;
use App\Models\Oferta;
use Carbon\Carbon;
use App\Models\Empresa;
use App\Mail\EstadoCandidaturaMailable;
use App\Mail\NuevaInscripcionEmpresa;
use Illuminate\Support\Facades\Mail;

class PostulacionController extends Controller
{
    public function index()
    {
        $candidato = Candidato::where('id_usuario', Auth::id())->first();

        if (!$candidato) {
            return redirect()->route('buscador')->with('error', 'Debes completar tu perfil de candidato para ver tus postulaciones.');
        }

        $postulaciones = Inscripcion::with(['oferta.datosEmpresa'])
            ->where('id_candidato', $candidato->id_candidato)
            ->orderBy('fecha_inscripcion', 'DESC')
            ->get();

        return view('candidato.inscripciones', compact('postulaciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_oferta' => 'required|exists:ofertas,id'
        ]);

        $candidato = Candidato::where('id_usuario', Auth::id())->first();

        if (!$candidato) {
            return back()->with('error', 'Necesitas un perfil de candidato para inscribirte.');
        }

        $id_oferta = $request->input('id_oferta');

        $existe = Inscripcion::where('id_oferta', $id_oferta)
            ->where('id_candidato', $candidato->id_candidato)
            ->exists();

        if ($existe) {
            return back()->with('info', 'Ya estás inscrito en esta oferta.');
        }

        $inscripcion = Inscripcion::create([
            'id_oferta' => $id_oferta,
            'id_candidato' => $candidato->id_candidato,
            'fecha_inscripcion' => Carbon::now(),
            'estado' => 'pendiente'
        ]);

        try {
            $oferta = Oferta::with('datosEmpresa.usuario')->find($id_oferta);
            $perfilCandidato = \App\Models\Candidato::where('id_usuario', Auth::id())->first();

            if ($oferta && $oferta->datosEmpresa && $oferta->datosEmpresa->usuario) {
                Mail::to($oferta->datosEmpresa->usuario->email)->send(
                    new NuevaInscripcionEmpresa($perfilCandidato, $oferta)
                );
            }
            return back()->with('success', '¡Te has inscrito correctamente y hemos avisado a la empresa!');
        } catch (\Exception $e) {
            Log::error("Error enviando correo a empresa: " . $e->getMessage());
            return back()->with('success', '¡Te has inscrito correctamente! (Nota: Hubo un problema temporal con la notificación a la empresa).');
        }
    }

    public function destroy(Request $request)
    {
        $id_usuario = Auth::id();
        $candidato = Candidato::where('id_usuario', $id_usuario)->first();
        $id_oferta = $request->input('id_oferta');

        if (!$candidato) {
            return back()->with('error', 'No se pudo verificar tu identidad de candidato.');
        }

        $borrado = Inscripcion::where('id_oferta', $id_oferta)
            ->where('id_candidato', $candidato->id_candidato)
            ->delete();

        if ($borrado) {
            return back()->with('success', 'Inscripción cancelada correctamente.');
        }

        return back()->with('error', 'No se pudo cancelar la inscripción o ya no existe.');
    }

    public function actualizarEstado(Request $request)
    {
        $request->validate([
            'id_inscripcion' => 'required|exists:inscripciones,id',
            'nuevo_estado' => 'required|string|in:pendiente,revision,finalista,aceptado,rechazado'
        ]);

        $inscripcion = Inscripcion::with(['oferta', 'candidato.usuario'])->findOrFail($request->id_inscripcion);

        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->first();

        if (!$perfilEmpresa || $inscripcion->oferta->id_empresa !== $perfilEmpresa->id_empresa) {
            return back()->with('error', 'No tienes permiso para gestionar esta candidatura.');
        }

        $inscripcion->estado = $request->nuevo_estado;
        $inscripcion->save();

        try {
            Mail::to($inscripcion->candidato->usuario->email)->send(
                new EstadoCandidaturaMailable(
                    $inscripcion->candidato->usuario,
                    $inscripcion->oferta,
                    $inscripcion->estado
                )
            );
        } catch (\Exception $e) {
            return back()->with('success', 'Estado actualizado, pero hubo un problema enviando el email.');
        }
        return back()->with('success', 'Estado actualizado a: ' . ucfirst($request->nuevo_estado) . ' y candidato notificado.');
    }
}
