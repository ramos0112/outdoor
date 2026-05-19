{{-- 
    resources/views/layouts/guest-noindex.blade.php
    
    Layout para páginas de autenticación (login, register, password reset)
    Inyecta automáticamente: <meta name="robots" content="noindex, nofollow">
    
    USO en vistas de login/register:
    @extends('layouts.guest-noindex')
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ⚠️ META ROBOTS PARA PROTEGER PÁGINAS DE AUTENTICACIÓN ⚠️ --}}
        <meta name="robots" content="noindex, nofollow, noarchive">
        <meta name="googlebot" content="noindex, nofollow">
        <meta name="robots" content="noimageindex">

        <title>{{ config('app.name', 'Laravel') }} - Acceso Restringido</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>

{{-- 
    NOTAS DE SEGURIDAD:
    
    1. Meta name="robots" content="noindex, nofollow, noarchive"
       - noindex: No indexar esta página en motores de búsqueda
       - nofollow: No seguir links en esta página
       - noarchive: No guardar caché en Google
    
    2. Meta name="googlebot" content="noindex, nofollow"
       - Directiva específica para Googlebot
    
    3. Meta name="robots" content="noimageindex"
       - Evita que Google indexe imágenes (extra safety)

    4. Este layout también previene que se cachee sensibles información:
       - Credentials
       - CSRF tokens
       - Sesiones activas

    IMPLEMENTACIÓN EN VISTAS:
    
    En resources/views/auth/login.blade.php:
    @extends('layouts.guest-noindex')
    
    En resources/views/auth/register.blade.php:
    @extends('layouts.guest-noindex')
    
    En resources/views/auth/forgot-password.blade.php:
    @extends('layouts.guest-noindex')
    
    En resources/views/auth/reset-password.blade.php:
    @extends('layouts.guest-noindex')
--}}
