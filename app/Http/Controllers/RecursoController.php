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
    
     public function store(RecursoRequest $request)
{
    try {
        $validatedData = $request->validated();
        $validatedData['estado'] = 1; // Estado por defecto 'disponible'
        Recurso::create($validatedData);

        return redirect()->back()->with('success', 'Recurso creado exitosamente.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput(); // Aquí no es necesario agregar el mensaje de error genérico
    }
}


 


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $recurso = Recurso::find($id);

    if ($recurso) {
        return response()->json($recurso);
    } else {
        return response()->json(['message' => 'Recurso no encontrado'], 404);
    }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $recurso = Recurso::findOrFail($id);

    // Validar la solicitud
    $request->validate([
        'nro_serie' => 'required|unique:recursos,nro_serie,' . $recurso->id,
        'id_categoria' => 'required',
        'id_marca' => 'required',
        'estado' => 'required|in:1,2,3,4', // Añade aquí los valores permitidos para estado
    ]);

    // Actualizar los campos
    $recurso->nro_serie = $request->nro_serie;
    $recurso->id_categoria = $request->id_categoria;
    $recurso->id_marca = $request->id_marca;
    $recurso->estado = $request->estado; // Asegúrate de que estás asignando el valor de estado

    $recurso->save();

    return redirect()->route('recursos.index')->with('success', 'Recurso actualizado correctamente');
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