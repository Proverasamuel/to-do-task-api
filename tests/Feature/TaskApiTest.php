<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh');

        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->token = $this->user->createToken('TestToken')->plainTextToken;
    }

    private function withAuthHeaders(): array
    {
        return ['Authorization' => 'Bearer ' . $this->token];
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $response = $this->withHeaders($this->withAuthHeaders())
            ->postJson('/api/tasks', [
                'title' => 'Test Task',
                'description' => 'Some description',
            ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Task']);
    }

    /** @test */
    public function user_can_list_his_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->withAuthHeaders())
            ->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function user_can_update_task_status()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);

        $response = $this->withHeaders($this->withAuthHeaders())
            ->putJson("/api/tasks/{$task->id}", [
                'status' => 'completed',
            ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed']);
    }

    /** @test */
    public function user_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->withAuthHeaders())
            ->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_filter_tasks_by_status()
    {
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'completed']);

        $response = $this->withHeaders($this->withAuthHeaders())
            ->getJson('/api/tasks/status/pending');

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'pending'])
                 ->assertJsonMissing(['status' => 'completed']);
    }

    /** @test */
    public function validation_fails_when_creating_task_without_title()
    {
        $response = $this->withHeaders($this->withAuthHeaders())
            ->postJson('/api/tasks', [
                'description' => 'Missing title'
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function validation_fails_with_invalid_status()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders($this->withAuthHeaders())
            ->putJson("/api/tasks/{$task->id}", [
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['status']);
    }
}
