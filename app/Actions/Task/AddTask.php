<?php 
namespace App\Actions\Task;

use Illuminate\Http\Request;
use App\Models\Task;

class AddTask {

    public function handle(Request $request) 
    {
        $task = new Task();
        $task->title = $request->input('title');
        $task->description = $request->input('description');
        $task->user_id = auth()->id();
        $task->status = $request->input('status');
        $task->save();

        return $task;
    }
}