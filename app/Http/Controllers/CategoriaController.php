<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\QueryException; 

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categorias = Categoria::paginate();

        return view('categoria.index', compact('categorias'))
            ->with('i', ($request->input('page', 1) - 1) * $categorias->perPage());
    }

    
    
    
    /**
     * Store a newly created resource in storage.
     */
    
    
    
     public function store(CategoriaRequest $request): RedirectResponse
    {
        // Validación y creación de la categoría
        Categoria::create($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Categoría creada exitosamente.');
    }








    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Obtener la categoría por su ID
        $categoria = Categoria::findOrFail($id);

        // Devolver los datos de la categoría en formato JSON para usar con AJAX
        return response()->json($categoria);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, $id): RedirectResponse
    {
        // Encontrar la categoría por su ID
        $categoria = Categoria::findOrFail($id);

        // Actualizar los datos de la categoría
        $categoria->update($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    

    public function destroy($id)
    {
        try {
            // Intenta eliminar la categoría
            $categoria = Categoria::findOrFail($id);
            $categoria->delete();

            return response()->json(['message' => 'Categoría eliminada correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar esta categoría porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar la categoría.'], 500);
        }
    }

}