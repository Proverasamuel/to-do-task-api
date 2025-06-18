<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
/**
 * @OA\Info(
 *     title="To-Do List API",
 *     version="1.0.0",
 *     description="API para gerenciamento de tarefas dos usuários"
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     securityScheme="sanctum"
 * )
 */
class TaskController extends Controller
{
     use AuthorizesRequests;
       /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Listar todas as tarefas do usuário autenticado",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas retornada com sucesso"
     *     )
     * )
     */
    public function index()
    {
        try {
            $tasks = auth()->user()->tasks;
            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao listar tarefas: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao listar tarefas'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Criar uma nova tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title"},
     *             @OA\Property(property="title", type="string", example="Comprar pão"),
     *             @OA\Property(property="description", type="string", example="Ir ao mercado comprar pão")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $task = auth()->user()->tasks()->create($data);

            return response()->json($task, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao criar tarefa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao criar tarefa'], 500);
        }
    }
 /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Atualizar o status de uma tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed"}, example="completed")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Tarefa atualizada"),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function update(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);

            $data = $request->validate([
                'status' => 'required|in:pending,in_progress,completed'
            ]);

            $task->update($data);

            return response()->json($task, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => 'Acesso negado'], 403);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar tarefa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao atualizar tarefa'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Deletar uma tarefa",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Tarefa deletada")
     * )
     */
    public function destroy(Task $task)
    {
        try {
            $this->authorize('delete', $task);

            $task->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json(['error' => 'Acesso negado'], 403);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar tarefa: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar tarefa'], 500);
        }
    }

     /**
     * @OA\Get(
     *     path="/api/tasks/status/{status}",
     *     tags={"Tasks"},
     *     summary="Filtrar tarefas por status",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         description="Status da tarefa (pending, in_progress, completed)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Tarefas filtradas"),
     *     @OA\Response(response=422, description="Status inválido")
     * )
     */
    public function filterByStatus($status)
    {
        try {
            if (!in_array($status, ['pending', 'in_progress', 'completed'])) {
                return response()->json(['error' => 'Invalid status'], 422);
            }

            $tasks = auth()->user()->tasks()->where('status', $status)->get();

            return response()->json($tasks, 200);
        } catch (\Exception $e) {
            Log::error('Erro ao filtrar tarefas: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao filtrar tarefas'], 500);
        }
    }
}
