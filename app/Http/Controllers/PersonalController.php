<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PersonalRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Tipodoc;
use Illuminate\Support\Facades\Http;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
{
    $personals = Personal::paginate();
    $tipodocs = Tipodoc::all();

    return view('personal.index', compact('personals','tipodocs'))
        ->with('i', ($request->input('page', 1) - 1) * $personals->perPage());
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
{
    $tipodocs = Tipodoc::select('id', 'abreviatura')->get();
    $personal = new Personal(); // Crear una instancia nueva y vacía
    return view('personal.create', compact('tipodocs', 'personal'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validación de los datos
    $request->validate([
        'id_tipodocs' => 'required|exists:tipodocs,id',
        'nro_documento' => 'required|numeric',
        'telefono' => 'required',
        'nombres' => 'required',
        'a_paterno' => 'required',
        'a_materno' => 'required',
        'cargo' => 'required',
    ]);

    $personal = new Personal();
    $personal->id_tipodocs = $request->id_tipodocs;
    $personal->nro_documento = $request->nro_documento;
    $personal->telefono = $request->telefono;
    $personal->nombres = $request->nombres;
    $personal->a_paterno = $request->a_paterno;
    $personal->a_materno = $request->a_materno;
    $personal->cargo = $request->cargo;

    $personal->save();
    return redirect()->back()->with('success', 'Personal agregado exitosamente.');
}


    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $personal = Personal::find($id);

        return view('personal.show', compact('personal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $personal = Personal::find($id);
    return response()->json($personal);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $personal = Personal::findOrFail($id);
    $personal->update($request->all());

    return redirect()->route('personals.index')->with('success', 'Personal actualizado correctamente');
}

    public function destroy($id): RedirectResponse
    {
        Personal::find($id)->delete();

        return Redirect::route('personals.index')
            ->with('success', 'Personal deleted successfully');
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
