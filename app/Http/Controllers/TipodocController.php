<?php

namespace App\Http\Controllers;

use App\Models\Tipodoc;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TipodocRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TipodocController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tipodocs = Tipodoc::paginate();

        return view('tipodoc.index', compact('tipodocs'))
            ->with('i', ($request->input('page', 1) - 1) * $tipodocs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $tipodoc = new Tipodoc();

        return view('tipodoc.create', compact('tipodoc'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TipodocRequest $request): RedirectResponse
    {
        Tipodoc::create($request->validated());

        return Redirect::route('tipodocs.index')
            ->with('success', 'Tipodoc created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $tipodoc = Tipodoc::find($id);

        return view('tipodoc.show', compact('tipodoc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $tipodoc = Tipodoc::find($id);

        return view('tipodoc.edit', compact('tipodoc'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TipodocRequest $request, Tipodoc $tipodoc): RedirectResponse
    {
        $tipodoc->update($request->validated());

        return Redirect::route('tipodocs.index')
            ->with('success', 'Tipodoc updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Tipodoc::find($id)->delete();

        return Redirect::route('tipodocs.index')
            ->with('success', 'Tipodoc deleted successfully');
    }
}
