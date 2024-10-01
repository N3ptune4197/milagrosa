<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB

class ChartController extends Controller
{
    public function getDocentesConPrestamos()
    {
        // Consulta para obtener los 5 docentes con más préstamos
        $docentes = DB::table('prestamos as pr')
            ->join('personals as p', 'pr.idPersonal', '=', 'p.id')
            ->select('p.nombres', 'p.a_paterno', 'p.a_materno', DB::raw('COUNT(*) AS total_prestamos'))
            ->groupBy('p.id', 'p.nombres', 'p.a_paterno', 'p.a_materno') // Asegúrate de agrupar por todas las columnas seleccionadas
            ->orderBy('total_prestamos', 'DESC')
            ->limit(5)
            ->get();

        // Retorna los datos como JSON
        return response()->json($docentes);
    }
    
    public function getDocentesConPrestamosActivos()
    {
        $docentesActivos = DB::table('prestamos as pr')
            ->join('personals as p', 'pr.idPersonal', '=', 'p.id')
            ->select('p.nombres', 'p.a_paterno', 'p.a_materno', DB::raw('COUNT(*) AS total_prestamos'))
            ->where('pr.estado', 'activo') // Condición para solo préstamos activos
            ->groupBy('p.id', 'p.nombres', 'p.a_paterno', 'p.a_materno')
            ->orderBy('total_prestamos', 'DESC')
            ->limit(5)
            ->get();

        return response()->json($docentesActivos);
    }
}
