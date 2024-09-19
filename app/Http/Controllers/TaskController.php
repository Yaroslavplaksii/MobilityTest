<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use Illuminate\Http\Request;
use App\Models\Task;

use App\Actions\Task\AddTask;
use App\Actions\Task\UpdateTask;
use App\Actions\Task\GetAllTasks;
use App\Actions\Task\GetTaskById;
use App\Actions\Task\DeleteTask;

use App\Http\Resources\TaskResource;

class TaskController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GetAllTasks $getAllTasks)
    {
        try {
            $tasks = $getAllTasks->handle();
            return TaskResource::collection($tasks);
        } catch (\Exception $e) {
            return ['status' => 500, 'msg' => 'Помилка: ' . $e->getMessage()];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request, AddTask $addTask)
    {  
        try {
            $task = $addTask->handle($request);
            if ($task->id) {
                return ['status' => 200, 'msg' => 'Завдання створено успішно!'];
            } else {
                return ['status' => 400, 'msg' => 'Не вдалося створити завдання. Спробуйте пізніше'];
            }
        } catch (\Exception $e) {
            return ['status' => 500, 'msg' => 'Помилка: ' . $e->getMessage()];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, GetTaskById $getTaskById)
    { 
        try {
            $task = $getTaskById->handle($id);
            return new TaskResource($task);
        } catch (\Exception $e) {
            return ['status' => 500, 'msg' => 'Помилка: ' . $e->getMessage()];
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, $id, UpdateTask $updateTask)
    {
        try {
            $task = $updateTask->handle($request, $id);
            if ($task->id) {
                return ['status' => 200, 'msg' => 'Завдання успішно оновлено!'];
            } else {
                return ['status' => 400, 'msg' => 'Не вдалося оновити завдання. Спробуйте пізніше'];
            }
        } catch (\Exception $e) {
            return ['status' => 500, 'msg' => 'Помилка: ' . $e->getMessage()];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, DeleteTask $deleteTask)
    {
        try {
            $task = $deleteTask->handle($id);
            if ($task) {
                return ['status' => 200, 'msg' => 'Завдання видалено успішно'];
            } else {
                return ['status' => 400, 'msg' => 'Не вдалося видалити завданняю Спробуйте пізніше'];
            }
        } catch (\Exception $e) {
            return ['status' => 500, 'msg' => 'Помилка: ' . $e->getMessage()];
        }
    }
}
