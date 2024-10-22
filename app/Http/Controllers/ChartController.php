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
            ->limit(3)
            ->get();

        // Retorna los datos como JSON
        return response()->json($docentes);
    }
    
    public function getDocentesConPrestamosActivos()
    {
        $docentesActivos = DB::table('prestamos as pr')
            ->join('personals as p', 'pr.idPersonal', '=', 'p.id')
            ->select('p.nombres', 'p.a_paterno', 'p.a_materno', DB::raw('COUNT(*) AS prestamos_activos'))
            ->where('pr.estado', 'activo') // Condición para solo préstamos activos
            ->groupBy('p.id', 'p.nombres', 'p.a_paterno', 'p.a_materno')
            ->orderBy('prestamos_activos', 'DESC')
            ->get();

        return response()->json($docentesActivos);
    }


    public function getCategoriasMasUtilizadas() {
        $categoriasMasUtilizadas = DB::table('detalleprestamos as dp')
            ->join('recursos as r', 'dp.id_recurso', '=', 'r.id')
            ->join('categorias as c', 'r.id_categoria', '=', 'c.id')
            ->select('c.nombre', DB::raw('COUNT(dp.id_recurso) AS cantidad_prestamos'))
            ->groupBy('c.nombre') // Agrupamos por la categoría
            ->orderBy('cantidad_prestamos', 'DESC')
            ->take(5) // Limitar a las 5 categorías más utilizadas
            ->get();
    
        return response()->json($categoriasMasUtilizadas);
    }
    
    
}
