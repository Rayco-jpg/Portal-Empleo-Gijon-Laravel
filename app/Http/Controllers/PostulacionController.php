<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Inscripcion;
use App\Models\Candidato;
use App\Models\Oferta;
use Carbon\Carbon;
use App\Models\Empresa;

class PostulacionController extends Controller
{
    /**
     * Muestra la lista de inscripciones del candidato
     */
    public function index()
    {
        // 1. Buscamos el perfil del candidato asociado al usuario logueado
        // Es vital usar first() para obtener el objeto y luego acceder a su id_candidato
        $candidato = Candidato::where('id_usuario', Auth::id())->first();

        if (!$candidato) {
            return redirect()->route('buscador')->with('error', 'Debes completar tu perfil de candidato para ver tus postulaciones.');
        }

        // 2. Cargamos las inscripciones usando el id_candidato de la tabla candidatos
        // Usamos with() para cargar la oferta y la empresa de forma eficiente (Eager Loading)
        $postulaciones = Inscripcion::with(['oferta.datosEmpresa'])
            ->where('id_candidato', $candidato->id_candidato)
            ->orderBy('fecha_inscripcion', 'DESC')
            ->get();

        return view('candidato.inscripciones', compact('postulaciones'));
    }

    /**
     * Crea una nueva inscripción (Postularse)
     */
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

        // Usamos updateOrCreate o un simple check para evitar duplicados
        $existe = Inscripcion::where('id_oferta', $id_oferta)
            ->where('id_candidato', $candidato->id_candidato)
            ->exists();

        if ($existe) {
            return back()->with('info', 'Ya estás inscrito en esta oferta.');
        }

        Inscripcion::create([
            'id_oferta' => $id_oferta,
            'id_candidato' => $candidato->id_candidato,
            'fecha_inscripcion' => Carbon::now(),
            'estado' => 'pendiente'
        ]);

        return back()->with('success', '¡Te has inscrito correctamente!');
    }

    /**
     * Cancela una inscripción (Candidato)
     */
    public function destroy(Request $request)
    {
        $id_usuario = Auth::id();
        $candidato = Candidato::where('id_usuario', $id_usuario)->first();
        $id_oferta = $request->input('id_oferta');

        if (!$candidato) {
            return back()->with('error', 'No se pudo verificar tu identidad de candidato.');
        }

        // Borramos la inscripción específica de este candidato para esta oferta
        $borrado = Inscripcion::where('id_oferta', $id_oferta)
            ->where('id_candidato', $candidato->id_candidato)
            ->delete();

        if ($borrado) {
            return back()->with('success', 'Inscripción cancelada correctamente.');
        }

        return back()->with('error', 'No se pudo cancelar la inscripción o ya no existe.');
    }

    /**
     * Actualiza el estado de una inscripción (Acción de la Empresa)
     * He añadido validación de propiedad para seguridad.
     */
    public function actualizarEstado(Request $request)
    {
        $request->validate([
            'id_inscripcion' => 'required|exists:inscripciones,id',
            // Añadimos todos los estados que usas en tu select de la vista
            'nuevo_estado' => 'required|string|in:pendiente,revision,finalista,aceptado,rechazado'
        ]);

        $inscripcion = Inscripcion::with('oferta')->findOrFail($request->id_inscripcion);

        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->first();

        if (!$perfilEmpresa || $inscripcion->oferta->id_empresa !== $perfilEmpresa->id_empresa) {
            return back()->with('error', 'No tienes permiso para gestionar esta candidatura.');
        }

        $inscripcion->estado = $request->nuevo_estado;
        $inscripcion->save();

        return back()->with('success', 'Estado actualizado a: ' . ucfirst($request->nuevo_estado));
    }
}
