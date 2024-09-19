<?php 
namespace App\Actions\Task;

use App\Models\Task;

class GetTaskById {

    public function handle(string $id): Task
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
       
        return $task;
    }
}