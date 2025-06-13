<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UsuarioController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:perfil.ver')->only(['Perfil']);
    }

    public function Perfil()
    {
        return view('profile/Profile');
    }
}
