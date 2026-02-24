<?php

namespace App\Http\Controllers;

use App\Models\PresensiKaryawan;
use Illuminate\Http\Request;

class PresensiKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('on-working-page');
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
    public function show(PresensiKaryawan $presensiKaryawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PresensiKaryawan $presensiKaryawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresensiKaryawan $presensiKaryawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresensiKaryawan $presensiKaryawan)
    {
        //
    }
}
