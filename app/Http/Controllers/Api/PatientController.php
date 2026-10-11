<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // lista os pacientes, com opcao de pesquisa parcial pelo nome
    public function index(Request $request): JsonResponse
    {
        // with() realizando eager loading para evitar N+1 queries, buscando apenas os campos id e name da unidade de saúde relacionada.
        $query = Patient::with('healthUnit:id,name');

        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%'.$request->query('search').'%'
            );
        }

        $patients = $query->orderBy('name')->get();

        return response()->json($patients);
    }

    // cadastra um paciente utilizando os dados validados.
    public function store(PatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());

        $patient->load('healthUnit:id,name');

        return response()->json([
            'message' => 'Paciente cadastrado com sucesso.',
            'data' => $patient,
        ], 201);
    }

    // retorna os dados de um paciente especifico.
    public function show(Patient $patient): JsonResponse
    {
        $patient->load('healthUnit:id,name');

        return response()->json($patient);
    }

    // atualiza os dados de um paciente existente.
    public function update(
        PatientRequest $request,
        Patient $patient
    ): JsonResponse {
        $patient->update($request->validated());

        $patient->load('healthUnit:id,name');

        return response()->json([
            'message' => 'Paciente atualizado com sucesso.',
            'data' => $patient,
        ]);
    }

    // exclui um paciente pelo identificador.
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(null, 204);
    }
}
