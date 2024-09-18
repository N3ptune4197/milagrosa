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

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Inicia la consulta con los filtros
        $query = Prestamo::with('detalleprestamos.recurso');
    
        // Filtrar por nombre del profesor (personal)
        if ($request->filled('personal_name')) {
            $query->whereHas('personal', function ($q) use ($request) {
                $q->where('nombres', 'like', '%' . $request->personal_name . '%')
                  ->orWhere('a_paterno', 'like', '%' . $request->personal_name . '%');
            });
        }
    
        // Filtrar por rango de fechas
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('fecha_prestamo', [$request->start_date, $request->end_date]);
        }
    
        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
    
        // Ordenar los préstamos por estado, priorizando los 'activos' primero
        $query->orderByRaw("CASE WHEN estado = 'activo' THEN 1 ELSE 2 END");
    
        // Obtener los resultados paginados después de aplicar los filtros y la ordenación
        $prestamos = $query->paginate();
    
        // Retornar la vista con los resultados filtrados
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
        $recursos = Recurso::where('estado', 1)->get();
        return view('prestamo.create', compact('prestamo', 'personals','recursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrestamoRequest $request): RedirectResponse
{
    // Validar la cantidad de recursos seleccionados y disponibles
    $cantidadRecursosSeleccionados = count($request->idRecurso);
    $cantidadRecursosDisponibles = Recurso::where('estado', 1)->count();

    if ($cantidadRecursosSeleccionados > $cantidadRecursosDisponibles) {
        return Redirect::back()->withErrors(['error' => 'No puedes seleccionar más recursos de los que están disponibles.']);
    }

    // Procesar cada recurso seleccionado como un préstamo independiente
    foreach ($request->idRecurso as $idRecurso) {
        $recurso = Recurso::find($idRecurso);

        // Verificar que el recurso esté disponible antes de procesar
        if ($recurso && $recurso->estado == 1) {
            // Crear un nuevo registro de préstamo para cada recurso
            $prestamo = Prestamo::create([
                'idPersonal' => $request->idPersonal,
                'fecha_prestamo' => now(),
                'fecha_devolucion' => $request->fecha_devolucion,
                'cantidad_total' => 1, // Siempre será 1 porque cada préstamo es para un recurso
                'observacion' => $request->observacion,
            ]);

            // Crear el detalle del préstamo asociado
            DetallePrestamo::create([
                'idprestamo' => $prestamo->id,
                'id_recurso' => $recurso->id,
            ]);

            // Actualizar el estado del recurso a "Prestado"
            $recurso->estado = 2; // Estado 2 significa "Prestado"
            $recurso->save();
        }
    }

    return Redirect::route('prestamos.index')
        ->with('success', 'Préstamos creados exitosamente para cada recurso seleccionado.');
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
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get(); // Obtener el listado de personal

        return view('prestamo.edit', compact('prestamo', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PrestamoRequest $request, Prestamo $prestamo): RedirectResponse
    {
        $validated = $request->validated();
        $validated['fecha_prestamo'] = $prestamo->fecha_prestamo; // Mantener la fecha de préstamo actual

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
    // Encuentra el préstamo
    $prestamo = Prestamo::find($id);

    if (!$prestamo) {
        return redirect()->route('prestamos.index')->with('error', 'Préstamo no encontrado.');
    }

    // Encuentra los detalles del préstamo relacionados
    $detallePrestamos = Detalleprestamo::where('idprestamo', $prestamo->id)->get();

    if ($detallePrestamos->isEmpty()) {
        return redirect()->route('prestamos.index')->with('error', 'No se encontraron detalles para este préstamo.');
    }

    // Marca el recurso como devuelto
    foreach ($detallePrestamos as $detalle) {
        // Encuentra el recurso relacionado
        $recurso = Recurso::find($detalle->id_recurso);
        if ($recurso) {
            $recurso->estado = 1; // Cambia el estado del recurso a 'Disponible'
            $recurso->save();
        }
    }

    // Actualiza el estado del préstamo a "desactivo" y guarda la fecha de devolución real
    $prestamo->estado = 'desactivo'; 
    $prestamo->fecha_devolucion_real = now(); // Guarda la fecha de devolución real (el momento en que se devuelve realmente)
    $prestamo->save();

    return redirect()->route('prestamos.index')->with('success', 'Préstamo marcado como devuelto.');
}


    
}
