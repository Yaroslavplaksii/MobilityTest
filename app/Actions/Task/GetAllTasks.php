<?php
namespace App\Actions\Task;

use App\Models\Task;

class GetAllTasks {

    public function handle()
    {

        $tasks = Task::where('user_id', auth()->id())->get();

        return $tasks;
    }
}