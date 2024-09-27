<?php

namespace App\Http\Controllers;

use App\Models\Recurso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RecursoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Marca;
use App\Models\Categoria;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $recursos = Recurso::with('categoria', 'marca')->paginate();
        $categorias = Categoria::select('id', 'nombre')->get();
        $marcas = Marca::select('id', 'nombre')->get();

        return view('recurso.index', compact('recursos', 'categorias', 'marcas'))
            ->with('i', ($request->input('page', 1) - 1) * $recursos->perPage());
    }

    

    /**
     * Store a newly created resource in storage.
     */
    
     public function store(RecursoRequest $request): RedirectResponse
    {
        // Validación y creación de la categoría
        Recurso::create($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Recurso creado exitosamente.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $recurso = Recurso::findOrFail($id);
        $marcas = Marca::select('id', 'nombre')->get();
        $categorias = Categoria::select('id', 'nombre')->get();
    
        // Devolver datos en formato JSON para la solicitud AJAX
        return response()->json($recurso);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(RecursoRequest $request, $id): RedirectResponse
    {
        // Encontrar la categoría por su ID
        $recurso = Recurso::findOrFail($id);

        // Actualizar los datos de la categoría
        $recurso->update($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Recurso actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Intenta eliminar la categoría
            $recurso = Recurso::findOrFail($id);
            $recurso->delete();

            return response()->json(['message' => 'Recurso eliminado correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar esta categoría porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar el recurso.'], 500);
        }
    }
}