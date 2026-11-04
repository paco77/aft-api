<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoachClientDeactivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_deactivating_a_coach_deactivates_all_their_clients(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $coach = User::factory()->create([
            'role' => 'coach',
            'is_active' => true,
        ]);

        $client1 = User::factory()->create([
            'role' => 'client',
            'coach_id' => $coach->id,
            'is_active' => true,
        ]);

        $client2 = User::factory()->create([
            'role' => 'client',
            'coach_id' => $coach->id,
            'is_active' => true,
        ]);

        $otherClient = User::factory()->create([
            'role' => 'client',
            'coach_id' => null,
            'is_active' => true,
        ]);

        // Toggle status of coach as superAdmin
        $response = $this->actingAs($superAdmin)->patch(route('admin.users.toggle-status', $coach));

        $response->assertSessionHas('success');
        $this->assertFalse($coach->fresh()->is_active);
        $this->assertFalse($client1->fresh()->is_active);
        $this->assertFalse($client2->fresh()->is_active);
        $this->assertTrue($otherClient->fresh()->is_active);
    }

    public function test_updating_coach_to_inactive_deactivates_clients(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $coach = User::factory()->create([
            'role' => 'coach',
            'is_active' => true,
        ]);

        $client = User::factory()->create([
            'role' => 'client',
            'coach_id' => $coach->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($superAdmin)->put(route('admin.users.update', $coach), [
            'username' => $coach->username,
            'name' => $coach->name,
            'email' => $coach->email,
            'role' => 'coach',
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertFalse($coach->fresh()->is_active);
        $this->assertFalse($client->fresh()->is_active);
    }
}

