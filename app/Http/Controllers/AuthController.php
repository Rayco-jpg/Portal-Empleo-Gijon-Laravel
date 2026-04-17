<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidato;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // --- REGISTRO ---

    public function showRegistro() { return view('auth.registro'); }

    public function registrar(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:8',
            'tipo_usuario' => 'required'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'tipo_usuario' => $request->tipo_usuario,
                    'fecha' => now()
                ]);

                if ($request->tipo_usuario == 'candidato') {
                    $nombre_cv = null;
                    if ($request->hasFile('cv')) {
                        $file = $request->file('cv');
                        $nombre_cv = time() . "_" . Str::slug($request->nombre_candidato) . ".pdf";
                        $file->move(public_path('uploads/curriculums'), $nombre_cv);
                    }
                    Candidato::create([
                        'id_usuario' => $user->id,
                        'nombre' => $request->nombre_candidato,
                        'apellidos' => $request->apellidos,
                        'curriculum' => $nombre_cv
                    ]);
                } else {
                    Empresa::create([
                        'id_usuario' => $user->id,
                        'nombre_empresa' => $request->nombre_empresa,
                        'sector' => $request->sector
                    ]);
                }
            });
            return back()->with('exito', '¡Registro completado!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // --- LOGIN / LOGOUT ---

    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Guardamos datos básicos en sesión para no consultar la DB en cada vista
            if ($user->tipo_usuario == 'empresa') {
                $perfil = Empresa::where('id_usuario', $user->id)->first();
                session(['id_perfil' => $perfil->id_empresa, 'nombre' => $perfil->nombre_empresa, 'tipo' => 'empresa']);
            } else {
                $perfil = Candidato::where('id_usuario', $user->id)->first();
                if ($perfil) {
                    session([
                        'id_perfil' => $perfil->id_candidato,
                        'nombre' => $perfil->nombre,
                        'tipo' => 'candidato',
                        'foto' => $perfil->foto
                    ]);
                }
            }
            return redirect()->intended('/');
        }
        return back()->with('error', 'Credenciales incorrectas.')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // --- RECUPERACIÓN DE PASSWORD ---

    public function showOlvido() { return view('auth.olvido_password'); }

    public function enviarEnlace(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $usuario = User::where('email', $request->email)->first();

        if ($usuario) {
            $token = Str::random(64);
            $usuario->update(['reset_token' => $token, 'token_expira' => Carbon::now()->addHour()]);
            $url = route('password.reset', ['token' => $token]);

            Mail::send('emails.recuperar', ['url' => $url], function ($message) use ($request) {
                $message->to($request->email)->subject('Recuperar Contraseña');
            });
        }
        return back()->with('enviado', 'Enlace enviado.');
    }

    public function showRestablecer($token)
    {
        $usuario = User::where('reset_token', $token)->where('token_expira', '>', Carbon::now())->first();
        if (!$usuario) return redirect()->route('password.request')->withErrors(['error' => 'Token inválido.']);
        return view('auth.restablecer_password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['token' => 'required', 'password' => 'required|min:8|confirmed']);
        $usuario = User::where('reset_token', $request->token)->first();

        if ($usuario) {
            $usuario->update(['password' => Hash::make($request->password), 'reset_token' => null, 'token_expira' => null]);
            return redirect()->route('login')->with('mensaje', 'Contraseña actualizada.');
        }
        return back()->withErrors(['error' => 'Error al actualizar.']);
    }
}
