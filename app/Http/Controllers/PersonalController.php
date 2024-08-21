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

        return view('personal.index', compact('personals'))
            ->with('i', ($request->input('page', 1) - 1) * $personals->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $personal = new Personal();

        $tipodocs = Tipodoc::select('id', 'abreviatura')->get();
        $personal = null;
        return view('personal.create', compact('tipodocs', 'personal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PersonalRequest $request): RedirectResponse
    {
        Personal::create($request->validated());

        return Redirect::route('personals.index')
            ->with('success', 'Personal created successfully.');
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
    public function edit($id): View
    {
        $personal = Personal::findOrFail($id);
    $tipodocs = Tipodoc::select('id', 'abreviatura')->get(); 
    return view('personal.edit', compact('personal', 'tipodocs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PersonalRequest $request, Personal $personal): RedirectResponse
    {
        $personal->update($request->validated());

        return Redirect::route('personals.index')
            ->with('success', 'Personal updated successfully');
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
