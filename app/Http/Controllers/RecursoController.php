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
use Illuminate\Validation\Rule;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Query base de los recursos con relaciones
        $query = Recurso::with('categoria', 'marca');

        // Filtrar por número de serie
        if ($request->filled('serial_number')) {
            $query->where('nro_serie', 'like', '%' . $request->serial_number . '%');
        }

        // Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('id_categoria', $request->categoria_id);
        }

        // Filtrar por marca
        if ($request->filled('marca_id')) {
            $query->where('id_marca', $request->marca_id);
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtrar por fecha de registro
        if ($request->filled('fecha_registro')) {
            $query->whereDate('fecha_registro', $request->fecha_registro);
        }

        // Paginación después de aplicar los filtros
        $recursos = $query->paginate();

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
        $request->validate([
            'nro_serie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recursos')->ignore($request->input('recurso_id')) // Ignorar en actualizaciones
            ],
        ]);
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
        $request->validate([
            'nro_serie' => [
                'required',
                'string',
                'max:255',
                Rule::unique('recursos')->ignore($id) // Ignorar el recurso actual
            ],
        ]);
        $recurso = Recurso::find($id);

        $recurso->nro_serie = $request->nro_serie;
        $recurso->id_categoria = $request->id_categoria;
        $recurso->id_marca = $request->id_marca;
        $recurso->estado = $request->estado;

        // No actualizamos 'fecha_registro', solo los campos relevantes
        $recurso->save();

        return redirect()->route('recursos.index')->with('success', 'Recurso actualizado con éxito.');
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