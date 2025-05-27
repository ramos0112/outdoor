@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('css')
    <style>
        /* Fondo de la página */
        body {
            background-image: url('{{ asset('storage/imagenes/fondo.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh; /* Asegúrate de que ocupe toda la pantalla */
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-right: 50px;
            padding-left: 50px;
        }

        .logo-img {
            max-width: 200px;  /* Ajusta el tamaño máximo */
            width: 100%;       /* Ajusta al contenedor */
            height: auto;      /* Mantiene la proporción */
        }

        
        /* Media Query para pantallas pequeñas */
        @media (max-width: 768px) {
            body {
                justify-content: center; /* Centra el formulario en pantallas pequeñas */
                padding-right: 0; /* Elimina el espacio de la derecha en pantallas pequeñas */
                padding-left: 0; /* Elimina el espacio de la izquierda en pantallas pequeñas */
            }

            .auth-container {
                padding: 20px; /* Menos padding para pantallas pequeñas */
            }
        }

        /* Media Query para pantallas extra grandes */
        @media (min-width: 1200px) {
            body {
                padding-right: 900px; 
                /* Más espacio en pantallas grandes */
            }
        }

    </style>
@stop
@section('auth_header')
    <div class="text-center mb-4">
        <!-- Usar el componente de logo de tu aplicación -->
        <x-logo class="logo-img"/>
    </div>
    <h1 class="h6 text-gray-900 text-center mb-6">{{ __('adminlte::adminlte.login_message') }}</h1>
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp



@section('auth_body')
    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
