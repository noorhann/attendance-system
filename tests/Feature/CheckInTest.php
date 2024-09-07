<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckInTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_check_in()
    {
        $user = User::factory()->create();

        $now = Carbon::now();
        Carbon::setTestNow($now);

        $response = $this->actingAs($user)->postJson('/api/user/checkIn');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'check_in' => $now, 
        ]);

    }
    
}
