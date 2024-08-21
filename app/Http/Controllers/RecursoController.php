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
    
        return view('recurso.index', compact('recursos'))
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
    public function store(RecursoRequest $request): RedirectResponse
{
    $validated = $request->validated();
    $validated['estado'] = 1; // Estado "DISPONIBLE"

    Recurso::create($validated);

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
    public function edit($id): View
    {
        $recurso = Recurso::findOrFail($id);
    $marcas = Marca::select('id', 'nombre')->get();
    $categorias = Categoria::select('id', 'nombre')->get();
    return view('recurso.edit', compact('recurso', 'marcas', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    // Validar la solicitud
    $request->validate([
        'nombre' => 'required|string|max:255',
        'id_categoria' => 'required|exists:categorias,id',
        'estado' => 'required|integer|in:1,2,3,4',
        'modelo' => 'nullable|string|max:255',
        'nro_serie' => 'nullable|string|max:255',
        'id_marca' => 'required|exists:marcas,id',
    ]);

    $recurso = Recurso::findOrFail($id);

   
    $recurso->update([
        'nombre' => $request->input('nombre'),
        'id_categoria' => $request->input('id_categoria'),
        'estado' => $request->input('estado'),
        'modelo' => $request->input('modelo'),
        'nro_serie' => $request->input('nro_serie'),
        'id_marca' => $request->input('id_marca'),
        'fecha_registro' => $recurso->fecha_registro, 
    ]);

    
    return redirect()->route('recursos.index')->with('success', 'Recurso actualizado exitosamente.');
}

    public function destroy($id): RedirectResponse
    {
        Recurso::find($id)->delete();

        return Redirect::route('recursos.index')
            ->with('success', 'Recurso deleted successfully');
    }
}
