<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PersonalRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $personals = Personal::paginate();
        return view('personal.index', compact('personals'))
            ->with('i', ($request->input('page', 1) - 1) * $personals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */



    /* public function create(): View
    {
        $personal = new Personal();

        return view('personal.create', compact('personal'));
    }
 */

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalRequest $request): RedirectResponse
    {
        // Validación
        $request->validate([
            'nro_documento' => [
                'required',
                'string',
                'max:255',
                Rule::unique('personals')->ignore($request->input('personal_id')) // Ignorar en actualizaciones
            ],
        ]);

        // Verifica si el número de documento ya existe
        if (Personal::where('nro_documento', $request->nro_documento)->exists()) {
            return redirect()->back()->with('error', 'El documento ya existe. Por favor, ingrese uno nuevo.');
        }

        // Crear el nuevo personal
        Personal::create($request->validated());

        return redirect()->back()->with('success', 'Personal creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */


    /* public function show($id): View
   {
       $personal = Personal::find($id);

       return view('personal.show', compact('personal'));
   } */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personal = Personal::findOrFail($id);
        return response()->json($personal);
    }

    /**
     * Update the specified resource in storage.
     */

    //  public function update(PersonalRequest $request, Personal $personal): RedirectResponse
    public function update(PersonalRequest $request, $id): RedirectResponse
    {
        // Validación
        $request->validate([
            'nro_documento' => [
                'required',
                'string',
                'max:255',
                Rule::unique('personals')->ignore($id) // Ignorar el personal actual
            ],
        ]);
        // Encontrar la categoría por su ID
        $personal = Personal::findOrFail($id);

        // Actualizar los datos de la categoría
        $personal->update($request->validated());

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Personal actualizada exitosamente.');
    }


    public function destroy($id)
    {
        try {
            // Intenta eliminar el personal
            $personal = Personal::findOrFail($id);
            $personal->delete();

            return response()->json(['message' => 'Personal eliminado correctamente.'], 200);
        } catch (QueryException $e) {
            // Si hay un error de restricción de integridad, captura la excepción
            if ($e->getCode() == 23000) { // Código de error de violación de clave foránea
                return response()->json(['message' => 'No se puede eliminar este Personal porque está relacionada con otros registros.'], 400);
            }

            return response()->json(['message' => 'Error al eliminar el Personal.'], 500);
        }
    }












    public function buscarDni($dni)
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImNhcmxvc2NoZXJvMTM0QGdtYWlsLmNvbSJ9.R_AH9D_aSHmHUK7K8O5WnLFzjt4NnpTRaZgybVhlZgg';
        $url = "https://dniruc.apisperu.com/api/v1/dni/{$dni}?token={$token}";

        $response = Http::get($url);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['success' => false], 404);
    }



}







