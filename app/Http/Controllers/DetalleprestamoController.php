<?php

namespace App\Http\Controllers;

use App\Models\Detalleprestamo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DetalleprestamoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DetalleprestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $detalleprestamos = Detalleprestamo::paginate();

        return view('detalleprestamo.index', compact('detalleprestamos'))
            ->with('i', ($request->input('page', 1) - 1) * $detalleprestamos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $detalleprestamo = new Detalleprestamo();

        return view('detalleprestamo.create', compact('detalleprestamo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DetalleprestamoRequest $request): RedirectResponse
    {
        Detalleprestamo::create($request->validated());

        return Redirect::route('detalleprestamos.index')
            ->with('success', 'Detalleprestamo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $detalleprestamo = Detalleprestamo::find($id);

        return view('detalleprestamo.show', compact('detalleprestamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $detalleprestamo = Detalleprestamo::find($id);

        return view('detalleprestamo.edit', compact('detalleprestamo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DetalleprestamoRequest $request, Detalleprestamo $detalleprestamo): RedirectResponse
    {
        $detalleprestamo->update($request->validated());

        return Redirect::route('detalleprestamos.index')
            ->with('success', 'Detalleprestamo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Detalleprestamo::find($id)->delete();

        return Redirect::route('detalleprestamos.index')
            ->with('success', 'Detalleprestamo deleted successfully');
    }
}
