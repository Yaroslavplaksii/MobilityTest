<?php
namespace App\Actions\Task;

use App\Models\Task;
use Illuminate\Http\Request;

class UpdateTask {

    public function handle(Request $request, string $id): Task
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = auth()->id();
        $task->status = $request->input('status');
        $task->save();

        return $task;
    }
}