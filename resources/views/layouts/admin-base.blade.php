{{-- 
    resources/views/layouts/admin-base.blade.php
    
    Layout base para TODAS las vistas del panel administrativo.
    Inyecta automáticamente: <meta name="robots" content="noindex, nofollow">
    
    Evita que Google indexe accidentalmente el panel, login y rutas sensibles.
    
    USO:
    En lugar de @extends('adminlte::page'), usa:
    @extends('layouts.admin-base')
--}}

@extends('adminlte::page')

@section('head')
    {{-- Inyectar meta robots noindex ANTES que cualquier otro contenido del head --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    
    {{-- Prevenir que Google busque en la caché --}}
    <meta name="Googlebot-Mobile" content="noindex, nofollow">
    
    {{-- Directiva para no seguir enlaces internos (extra safety) --}}
    <meta name="robots" content="noindex, nofollow, noarchive, nocache">
    
    {{-- Instrucciones para otros bots --}}
    <meta name="robots" content="noindex, nofollow, noimageindex">
@stop

{{-- El resto del contenido se renderiza normalmente desde layouts base de AdminLTE --}}
