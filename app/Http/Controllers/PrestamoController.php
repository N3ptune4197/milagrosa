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
use Illuminate\Support\Facades\DB;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Mostrar los datos que se envían desde el formulario


        // Inicia la consulta con los filtros
        $query = Prestamo::with('detalleprestamos.recurso');

        // Filtrar por id del personal
        if ($request->filled('personal_name')) {
            $query->whereHas('personal', function ($q) use ($request) {
                $q->where('id', $request->personal_name);
            });
        }

        // Filtrar por rango de fechas
        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->whereBetween('fecha_prestamo', [$request->start_date, $request->end_date]);
            } else {
                // Si solo se selecciona la fecha de inicio, mostrar desde esa fecha en adelante
                $query->where('fecha_prestamo', '>=', $request->start_date);
            }
        }

        // Filtrar por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('serial_number')) {
            $query->whereHas('detalleprestamos.recurso', function ($q) use ($request) {
                $q->where('nro_serie', 'like', '%' . $request->serial_number . '%');
            });
        }

        // Ordenar los préstamos por estado, priorizando los 'activos' primero y luego por fecha en orden descendente
        $prestamos = $query->orderByRaw("CASE WHEN estado = 'activo' THEN 1 ELSE 2 END")
            ->orderBy('fecha_prestamo', 'desc')
            ->paginate();

        // Obtener el listado de personal para el dropdown
        $personals = Personal::select('id', 'nombres', 'a_paterno')->get();
        $recursos = Recurso::where('estado', 1)->get();
        $recursosDisponiblesCount = Recurso::where('estado', 1)->count();
        $categorias = Categoria::all();

        // Extraer los números de serie únicos
        $uniqueRecursos = $prestamos->pluck('detalleprestamos.*.recurso')
            ->flatten()
            ->unique('nro_serie')
            ->filter() // Eliminar posibles nulos
            ->values(); // Reindexar la colección

        // Retornar la vista con los resultados filtrados y la lista de personal
        return view('prestamo.index', compact('prestamos', 'personals', 'recursos', 'recursosDisponiblesCount', 'categorias', 'uniqueRecursos'))
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
        return view('prestamo.create', compact('prestamo', 'personals', 'recursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PrestamoRequest $request): RedirectResponse
    {
        // Obtener los datos del formulario
        $idPersonal = $request->input('idPersonal');
        $observacion = $request->input('observacion');
        $idRecursos = $request->input('idRecurso'); // Array de recursos seleccionados
        $fechaDevoluciones = $request->input('fecha_devolucion'); // Array de fechas de devolución

        // Validar que haya recursos seleccionados
        if (empty($idRecursos) || !is_array($idRecursos)) {
            return back()->withErrors(['error' => 'Debes seleccionar al menos un recurso.']);
        }

        // Validar que las fechas de devolución estén presentes y sean válidas
        if (empty($fechaDevoluciones) || !is_array($fechaDevoluciones) || count($fechaDevoluciones) !== count($idRecursos)) {
            return back()->withErrors(['error' => 'Debes proporcionar una fecha de devolución para cada recurso seleccionado.']);
        }

        // Validar la cantidad de recursos disponibles
        $cantidadRecursosSeleccionados = count($idRecursos);
        $cantidadRecursosDisponibles = Recurso::where('estado', 1)->count();

        if ($cantidadRecursosSeleccionados > $cantidadRecursosDisponibles) {
            return Redirect::back()->withErrors(['error' => 'No puedes seleccionar más recursos de los que están disponibles.']);
        }

        // Iniciar una transacción para asegurar la integridad de los datos
        DB::beginTransaction();
        try {
            // Recorrer los recursos seleccionados para crear los detalles del préstamo
            foreach ($idRecursos as $index => $idRecursoItem) {
                $recurso = Recurso::find($idRecursoItem);

                // Verificar que el recurso esté disponible antes de procesar
                if ($recurso && $recurso->estado == 1) {
                    // Crear un préstamo individual para cada recurso
                    $prestamo = Prestamo::create([
                        'idPersonal' => $idPersonal,
                        'fecha_prestamo' => now(), // Fecha actual del sistema
                        'observacion' => $observacion
                    ]);

                    // Crear el detalle del préstamo asociado con 'fecha_devolucion'
                    DetallePrestamo::create([
                        'idprestamo' => $prestamo->id,
                        'id_recurso' => $recurso->id,
                        'fecha_devolucion' => $fechaDevoluciones[$index],
                    ]);

                    // Actualizar el estado del recurso a "Prestado"
                    $recurso->estado = 2; // Estado 2 significa "Prestado"
                    $recurso->save();
                } else {
                    // Si el recurso no está disponible, lanzar una excepción
                    throw new \Exception("El recurso con ID {$idRecursoItem} no está disponible.");
                }
            }

            // Confirmar la transacción
            DB::commit();

            return Redirect::route('prestamos.index')
                ->with('success', 'Préstamo creado exitosamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();

            return Redirect::back()->withErrors(['error' => 'Error al crear los préstamos: ' . $e->getMessage()]);
        }
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
    public function markAsReturned(Request $request, $id)
    {

        // Encuentra el detalle del préstamo
        $detallePrestamo = DetallePrestamo::with('prestamo', 'recurso')->find($id);

        if (!$detallePrestamo) {
            return redirect()->route('prestamos.index')->with('error', 'Detalle del préstamo no encontrado.');
        }

        // Encuentra el recurso relacionado
        $recurso = Recurso::find($detallePrestamo->id_recurso);

        if (!$recurso) {
            return redirect()->route('prestamos.index')->with('error', 'Recurso no encontrado.');
        }

        // Guarda la observación en el préstamo (no en detalleprestamos)
        $prestamo = $detallePrestamo->prestamo;
        $prestamo->observacion = $request->input('observacion', null); // El campo está en 'prestamos'
        $prestamo->save();

        // Actualiza la fecha de devolución en el detalle del préstamo
        $detallePrestamo->fecha_devolucion = now();
        $detallePrestamo->save();

        // Cambia el estado del recurso según el valor del campo 'estado'
        $nuevoEstado = $request->input('estado');

        // Verificamos si el estado es diferente de 1
        if ($nuevoEstado !== null) {
            if ($nuevoEstado != 1) {
                // Si no es 1 (disponible), entonces se marca como dañado (estado 4)
                $recurso->estado = 4; // Dañado
            } else {
                // Si es 1, se marca como disponible
                $recurso->estado = 1; // Disponible
            }

            // Guarda el estado del recurso
            $recurso->save();
        }

        // Verifica si todos los detalles del préstamo han sido devueltos
        if ($prestamo->detalleprestamos()->whereNull('fecha_devolucion')->count() == 0) {
            $prestamo->estado = 'desactivo';
            $prestamo->fecha_devolucion_real = now(); // Fecha de devolución real
            $prestamo->save();
        }

        return redirect()->route('prestamos.index')->with('success', 'Recurso marcado como devuelto.');
    }









}
