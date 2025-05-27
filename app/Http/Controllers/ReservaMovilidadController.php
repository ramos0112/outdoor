<?php

namespace App\Http\Controllers;

use App\Models\ReservaMovilidad;
use Illuminate\Http\Request;

class ReservaMovilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('reservasmovilidad.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservaMovilidad $reservaMovilidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservaMovilidad $reservaMovilidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservaMovilidad $reservaMovilidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservaMovilidad $reservaMovilidad)
    {
        //
    }
}
