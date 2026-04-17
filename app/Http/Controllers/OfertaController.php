<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Oferta;
use App\Models\Inscripcion;
use App\Models\Favorito;
use App\Models\Candidato;
use App\Models\Empresa;

class OfertaController extends Controller
{
    /**
     * Buscador de ofertas para el Candidato
     */
    public function index(Request $request)
    {
        $id_usuario = Auth::id();
        $perfilCandidato = $id_usuario ? Candidato::where('id_usuario', $id_usuario)->first() : null;
        $id_candidato = $perfilCandidato ? $perfilCandidato->id_candidato : 0;

        // 1. Alertas
        $hay_novedades = false;
        $conteo_nuevas = 0;
        $nombre_cat_alerta = "";
        $idsCategoriasUsuario = [];

        if ($id_usuario) {
            $idsCategoriasUsuario = DB::table('alertas')
                ->where('id_usuario', $id_usuario)
                ->pluck('id_categoria')
                ->toArray();

            if (!empty($idsCategoriasUsuario)) {
                $novedadesQuery = Oferta::whereIn('id_categoria', $idsCategoriasUsuario)
                    ->whereDate('fecha_oferta', now()->toDateString());

                $conteo_nuevas = $novedadesQuery->count();
                if ($conteo_nuevas > 0) {
                    $hay_novedades = true;
                    $nombre_cat_alerta = DB::table('categorias')
                        ->whereIn('id_categoria', $idsCategoriasUsuario)
                        ->pluck('nombre_categoria')
                        ->implode(', ');
                }
            }
        }

        // 2. Estadísticas
        $stats = [
            'inscripciones' => $id_candidato ? Inscripcion::where('id_candidato', $id_candidato)->count() : 0,
            'favoritos'     => $id_usuario ? Favorito::where('id_usuario', $id_usuario)->count() : 0,
            'visitas'       => $id_candidato ? DB::table('visitas_perfil')->where('id_candidato', $id_candidato)->count() : 0,
        ];

        // 3. Consulta Principal
        $query = Oferta::with(['datosEmpresa', 'categoriaRelacion'])
            ->addSelect([
                'es_favorito' => Favorito::whereColumn('id_oferta', 'ofertas.id')
                    ->where('id_usuario', $id_usuario ?? 0)
                    ->selectRaw('count(*)'),
                'estado_inscripcion' => Inscripcion::whereColumn('id_oferta', 'ofertas.id')
                    ->where('id_candidato', $id_candidato)
                    ->select('estado')
                    ->limit(1)
            ]);

        // Filtros
        if ($request->filled('puestos')) {
            $q = $request->puestos;
            $query->where(function ($s) use ($q) {
                $s->where('titulo', 'LIKE', "%$q%")
                    ->orWhereHas('datosEmpresa', function ($e) use ($q) {
                        $e->where('nombre_empresa', 'LIKE', "%$q%");
                    });
            });
        }

        if ($request->filled('zona')) $query->where('zona_gijon', $request->zona);
        if ($request->filled('jornada')) $query->where('jornada', $request->jornada);

        if ($request->has('ver_novedades') && !empty($idsCategoriasUsuario)) {
            $query->whereIn('id_categoria', $idsCategoriasUsuario)
                ->whereDate('fecha_oferta', now()->toDateString());
        }

        $ofertas = $query->orderBy('fecha_oferta', 'DESC')->get();

        // 4. Datos para el Mapa
        $puntosMapa = $ofertas->filter(fn($o) => (float)$o->latitud != 0)
            ->map(fn($o) => [
                'id'      => $o->id,
                'lat'     => (float)$o->latitud,
                'lng'     => (float)$o->longitud,
                'titulo'  => $o->titulo,
                'empresa' => $o->datosEmpresa->nombre_empresa ?? 'Empresa',
                'salario' => $o->salario ? $o->salario . ' €' : 'A convenir',
            ])->values();

        return view('candidato.buscador', compact('ofertas', 'hay_novedades', 'conteo_nuevas', 'nombre_cat_alerta', 'stats', 'puntosMapa'));
    }

    /**
     * Mis Ofertas (Vista Empresa)
     */
    public function misOfertas()
    {
        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $ofertas = Oferta::where('id_empresa', $perfilEmpresa->id_empresa)
            ->withCount('inscripciones')
            ->orderBy('fecha_oferta', 'DESC')
            ->get();

        return view('empresa.mis_ofertas', compact('ofertas'));
    }

    /**
     * Muestra el formulario para crear una nueva oferta (AÑADIDO)
     */
    public function create()
    {
        // Obtenemos las categorías para que la empresa pueda seleccionarlas en el formulario
        $categorias = DB::table('categorias')->get();
        return view('empresa.crear_oferta', compact('categorias'));
    }

    /**
     * Guarda la nueva oferta en la base de datos
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
            'id_categoria' => 'required',
        ]);

        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        Oferta::create([
            'titulo'       => $request->titulo,
            'descripcion'  => $request->descripcion,
            'id_categoria' => $request->id_categoria,
            'id_empresa'   => $perfilEmpresa->id_empresa,
            'zona_gijon'   => $request->zona_gijon,
            'salario'      => $request->salario,
            'jornada'      => $request->jornada,
            'experiencia'  => $request->experiencia,
            'latitud'      => $request->latitud,
            'longitud'     => $request->longitud,
            'fecha_oferta' => now()->toDateString(),
            'estado'       => 'activa'
        ]);

        // Redirige al listado de ofertas de la empresa (ruta definida en web.php)
        return redirect()->route('ofertas.index')->with('success', 'Oferta publicada correctamente.');
    }

    /**
     * Eliminar Oferta
     */
    public function destroy($id)
    {
        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $oferta = Oferta::where('id', $id)
            ->where('id_empresa', $perfilEmpresa->id_empresa)
            ->firstOrFail();

        $oferta->delete();
        return back()->with('success', 'Oferta eliminada.');
    }

    /**
     * Ver Detalle de oferta para el Candidato
     */
    public function show($id)
    {
        $id_usuario = Auth::id();
        $candidato = Candidato::where('id_usuario', $id_usuario)->first();
        $id_candidato = $candidato ? $candidato->id_candidato : null;

        $oferta = Oferta::with(['datosEmpresa', 'categoriaRelacion'])
            ->addSelect([
                'estado_inscripcion' => Inscripcion::where('id_oferta', $id)
                    ->where('id_candidato', $id_candidato)
                    ->select('estado')
                    ->limit(1)
            ])
            ->findOrFail($id);

        $mis_habilidades = $candidato->habilidades_clave ?? "Completa tu perfil para destacar";

        return view('candidato.ver_oferta', compact('oferta', 'mis_habilidades'));
    }

    /**
     * Muestra los candidatos inscritos a una oferta específica.
     */
    public function verCandidatos($id)
    {
        // 1. Verificamos que la oferta pertenezca a la empresa identificada
        $perfilEmpresa = Empresa::where('id_usuario', Auth::id())->firstOrFail();

        $oferta = Oferta::where('id', $id)
            ->where('id_empresa', $perfilEmpresa->id_empresa)
            ->firstOrFail();

        // 2. Obtenemos las inscripciones junto con los datos de los candidatos
        // Asegúrate de que el modelo Inscripcion tenga la relación 'candidato' definida
        $inscripciones = Inscripcion::with('candidato.usuario')
            ->where('id_oferta', $id)
            ->get();

        return view('empresa.ver_candidato', compact('oferta', 'inscripciones'));
    }
}
