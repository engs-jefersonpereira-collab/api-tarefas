<?php

namespace App\Http\Controllers;

use App\Http\Resources\TarefaResource;
use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function index()
    {
        return TarefaResource::collection(Tarefa::all());
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'titulo' => 'required|string|max:255',
            'concluida' => 'sometimes|boolean',
        ]);

      $tarefa = Tarefa::create($dados)->refresh();

        return (new TarefaResource($tarefa))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Tarefa $tarefa)
    {
        return new TarefaResource($tarefa);
    }

    public function update(Request $request, Tarefa $tarefa)
    {
        $dados = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'concluida' => 'sometimes|boolean',
        ]);

        $tarefa->update($dados);

        return new TarefaResource($tarefa);
    }

    public function destroy(Tarefa $tarefa)
    {
        $tarefa->delete();

        return response()->noContent();
    }
}