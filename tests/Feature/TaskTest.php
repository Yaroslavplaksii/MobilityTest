<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

use Faker\Factory as Faker;

class TaskTest extends TestCase
{
  use RefreshDatabase;
  private $task;
  private $faker;
  public function setUp(): void 
  {
      parent::setUp();
      $this->faker = Faker::create();
      $user = User::Factory()->create();
      Sanctum::actingAs($user);

      $this->task = Task::factory()->create([
        'title' => $this->faker->sentence(),
        'description' => $this->faker->paragraph(),
        'status' => $this->faker->numberBetween(0, 1),
        'user_id' => $this->faker->randomElement(User::all())['id']
    ]);
  }

    public function test_all_tasks_list(): void
    {
        $response = $this->get('/api/tasks');

        $response->assertStatus(200);
    }

    public function test_add_task_with_random_data(): void
    {
        $tid = $this->task->id;

        $this->assertDataBaseHas('tasks', [
            'id' => $tid
        ]);
    }

    public function test_add_task_with_selected_data(): void
    {
        $task = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->numberBetween(0, 1),
            'user_id' => $this->faker->randomElement(User::all())['id']
        ];
        $response = $this->post('/api/tasks/', $task);
        $response->assertStatus(200);
        $this->assertDataBaseHas('tasks', ['title' => $task['title']]);
    }

    public function test_update_task_use_the_first_record(): void
    {
        $task = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->numberBetween(0, 1),
            'user_id' => $this->faker->randomElement(User::all())['id']
        ];
        $taskID = Task::all()[0];
        $response = $this->put('/api/tasks/' . $taskID['id'], $task);
        $response->assertStatus(200);
        $this->assertDataBaseHas('tasks', ['title' => $task['title']]);
        $this->assertDataBaseHas('tasks', ['description' => $task['description']]);
        $this->assertDataBaseHas('tasks', ['status' => $task['status']]);
        $this->assertDataBaseHas('tasks', ['user_id' => $task['user_id']]);
    }

    public function test_delete_task_use_last_record(): void
    {
        $tasks = Task::all();
        $taskID = $tasks[count($tasks) - 1];
        $id = $taskID->id;
        $response = $this->delete('/api/tasks/' . $id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', [
            'id' => $id
        ]);
    }

    public function test_one_test_by_random_id(): void
    {
        $taskID = ['task_id' => $this->faker->randomElement(Task::all())['id']
    ];
        $response = $this->get('/api/tasks/' .  $taskID['task_id']);
        $response->assertStatus(200);
       
    }
}
