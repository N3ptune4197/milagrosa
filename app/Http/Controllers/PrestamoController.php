<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PrestamoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Detalleprestamo;
use App\Models\Recurso;
use App\Models\Historial;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
{
    $prestamos = Prestamo::with('detalleprestamos.recurso')->paginate();

    return view('prestamo.index', compact('prestamos'))
        ->with('i', ($request->input('page', 1) - 1) * $prestamos->perPage());
}


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $prestamo = new Prestamo();
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get(); // Obtener el listado de personal
        $recursos = Recurso::all();
        return view('prestamo.create', compact('prestamo', 'personals','recursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrestamoRequest $request): RedirectResponse
{
    $data = $request->validated();
    $data['fecha_prestamo'] = now(); 

    // Crear el préstamo
    $prestamo = Prestamo::create([
        'idPersonal' => $request->idPersonal,
        'fecha_prestamo' => now(),
        'fecha_devolucion' => $request->fecha_devolucion,
        'observacion' => $request->observacion,
    ]);

    // Guardar el detalle del préstamo
    DetallePrestamo::create([
        'idprestamo' => $prestamo->id,
        'id_recurso' => $request->idRecurso,
    ]);

    return Redirect::route('prestamos.index')
        ->with('success', 'Préstamo creado exitosamente.');
}
    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $prestamo = Prestamo::find($id);

        return view('prestamo.show', compact('prestamo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $prestamo = Prestamo::find($id);
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get(); 

        return view('prestamo.edit', compact('prestamo', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrestamoRequest $request, Prestamo $prestamo): RedirectResponse
    {
        $validated = $request->validated();
        $validated['fecha_prestamo'] = $prestamo->fecha_prestamo; // Mantener la fecha de prestamo actual

        $prestamo->update($validated);

        return Redirect::route('prestamos.index')
            ->with('success', 'Préstamo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Prestamo::find($id)->delete();

        return Redirect::route('prestamos.index')
            ->with('success', 'Préstamo eliminado exitosamente');
    }
    public function markAsReturned($id)
{
    
    $prestamo = Prestamo::find($id);

    if (!$prestamo) {
        return redirect()->route('prestamos.index')->with('error', 'Préstamo no encontrado.');
    }

    
    $detallePrestamos = Detalleprestamo::where('idprestamo', $prestamo->id)->get();

    // guardar cada detalle del prestamo (id) en historial
    foreach ($detallePrestamos as $detalle) {
        Historial::create([
            'id_detalle_prestamo' => $detalle->id,
        ]);
        
        
        $detalle->delete();
    }

    // Eliminar el préstamo
    $prestamo->delete();

    return redirect()->route('prestamos.index')->with('success', 'Préstamo marcado como devuelto y guardado en el historial.');
}

}
