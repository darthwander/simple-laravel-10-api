<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conta;

class ContaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Conta::select('numero_conta', 'saldo')->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = $request->all();
        

    }

    /**
     * Display the specified resource.
     */
    public function show(string $numero_conta)
    {
        $conta = Conta::where('numero_conta', $numero_conta)->first();

        if (!$conta) {
            return response()->json(['message' => 'Conta não encontrada'], 404);
        }

        return response()->json([
            'id' => $conta->numero_conta,
            'saldo' => $conta->saldo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
