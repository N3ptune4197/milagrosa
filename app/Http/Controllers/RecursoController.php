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
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $recurso = new Recurso();
        $marcas = Marca::select('id', 'nombre')->get();
        $categorias = Categoria::select('id', 'nombre')->get();

        return view('recurso.create', compact('recurso', 'marcas', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
{
    // Validación de los datos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'id_categoria' => 'required|exists:categorias,id',
        'nro_serie' => 'nullable|string|max:255',
        'id_marca' => 'required|exists:marcas,id',
    ]);

    // Crear nuevo recurso
    $recurso = new Recurso();
    $recurso->nombre = $validated['nombre'];
    $recurso->id_categoria = $validated['id_categoria'];
    $recurso->id_marca = $validated['id_marca'];
    $recurso->nro_serie = $validated['nro_serie'];
    $recurso->estado = 1; // Estado "DISPONIBLE"
    $recurso->fecha_registro = now(); // Fecha de registro automática

    // Guardar el recurso
    $recurso->save();

    return Redirect::route('recursos.index')
        ->with('success', 'Recurso creado exitosamente.');
}


    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $recurso = Recurso::find($id);

        return view('recurso.show', compact('recurso'));
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
        return response()->json([
            'id' => $recurso->id,
            'nombre' => $recurso->nombre,
            'id_categoria' => $recurso->id_categoria,
            'estado' => $recurso->estado,
            'nro_serie' => $recurso->nro_serie,
            'id_marca' => $recurso->id_marca
        ]);
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id',
            'estado' => 'required|integer|in:1,2,3,4',
            'nro_serie' => 'nullable|string|max:255',
            'id_marca' => 'required|exists:marcas,id',
        ]);

        // Encontrar el recurso
        $recurso = Recurso::findOrFail($id);

        // Actualizar los campos
        $recurso->nombre = $request->input('nombre');
        $recurso->id_categoria = $request->input('id_categoria');
        $recurso->estado = $request->input('estado');
        $recurso->nro_serie = $request->input('nro_serie');
        $recurso->id_marca = $request->input('id_marca');

        $recurso->save();

        return redirect()->route('recursos.index')->with('success', 'Recurso actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Recurso::find($id)->delete();

        return Redirect::route('recursos.index')
            ->with('success', 'Recurso eliminado exitosamente.');
    }
}