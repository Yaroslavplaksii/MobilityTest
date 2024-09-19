<?php 
namespace App\Actions\Task;

use App\Models\Task;

class DeleteTask {

    public function handle(string $id) 
    {
        $deletedTask = Task::where('id', $id)->where('user_id', auth()->id())->delete();

        return  $deletedTask;
    }
}