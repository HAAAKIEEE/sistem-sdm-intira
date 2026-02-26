<?php

namespace App\Http\Controllers;

use App\Models\ELearningKaryawan;
use Illuminate\Http\Request;

class ELearningKaryawanController extends Controller
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
    public function show(ELearningKaryawan $eLearningKaryawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ELearningKaryawan $eLearningKaryawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ELearningKaryawan $eLearningKaryawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ELearningKaryawan $eLearningKaryawan)
    {
        //
    }
}
