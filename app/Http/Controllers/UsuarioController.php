<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    //
    public function Perfil(){
        return view('profile/Profile');
    }
    public function Perfil2(){
        return view('profile/show');
    }

}
