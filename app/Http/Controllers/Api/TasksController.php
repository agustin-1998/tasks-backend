<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShowTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = auth()->user()->tasks()->orderBy("id","desc")->paginate(10);
        return response()->json([
            'data' => $tasks->items(), # Solo los elementos de la pagina actual
            'pagination' => [
                'current_page' => $tasks->currentPage(),
                'total_pages' => $tasks->lastPage(),
                'total_items' => $tasks->total(),
                'per_page' => $tasks->perPage(),
            ],
            'status' => 200,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        return transactional(function () use ($request) {
            $task = $request->validated();
            $task['user_id'] = auth()->user()->id;
            Task::create($task);
    
            return response()->json([
                'message' => 'Task created successfully!',
                'status' => 200 
            ], 200);
        });
    }



    /**
     * Show the the specified resource.
     */
    public function show($id)
    {
        $task = Task::where('id', $id)
                        ->where('user_id', auth()->id())
                        ->first();
        
        if (!$task) {
            return response()->json([
                'message' => 'Task not found or you do not have permission to view it.',
                'status' => 404,
            ], 404);
        }
        
        return response()->json([
            'message' => "Task found successfully",
            'data' => $task,
            'status' => 200,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        return transactional(function () use ($request, $task) {
    
            $task = Task::where('id', $task->id)
                        ->where('user_id', auth()->id())
                        ->first();
    
            if (!$task) {
                return response()->json([
                    'message' => 'Task not found or you do not have permission to update it.',
                    'status' => 403,
                ], 403);
            }
    
            $task->update($request->validated());
    
            return response()->json([
                'message' => 'Task updated successfully.',
                'data' => $task,
                'status' => 200
            ], 200);
        });
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        return transactional(function () use ($task) {
            
            $task = Task::where('id', $task->id)
            ->where('user_id', auth()->id())
            ->first();

            if (!$task) {
                return response()->json([
                    'message' => 'Task not found or you do not have permission to delete it.',
                    'status' => 403,
                ], 403);
            }
            
            $task->delete();
            
            return response()->json([
                'message' => 'Task deleted successfully.',
                'status' => 200,
            ], 200);
        });
    }
}
