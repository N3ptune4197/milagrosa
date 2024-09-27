<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MarcaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\QueryException; 

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $marcas = Marca::paginate();

        return view('marca.index', compact('marcas'))
            ->with('i', ($request->input('page', 1) - 1) * $marcas->perPage());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MarcaRequest $request): RedirectResponse
    {
        Marca::create($request->validated());
        return redirect()->back()->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return response()->json($marca);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MarcaRequest $request, $id): RedirectResponse
    {
        $marca = Marca::findOrFail($id);
        $marca->update($request->validated());
        return redirect()->back()->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $marca = Marca::findOrFail($id);
            $marca->delete();
            return response()->json(['message' => 'Marca eliminada correctamente.'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === 23000) {
                return response()->json(['message' => 'No se puede eliminar esta marca porque está relacionada con otros registros.'], 400);
            }
            return response()->json(['message' => 'Error al eliminar la marca.'], 500);
        }
    }
}
