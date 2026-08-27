<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserManagementTest extends TestCase
{
    public function test_user_model_instantiation(): void
    {
        $user = new User([
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'client',
            'coach_id' => 2,
        ]);

        $this->assertEquals('testuser', $user->username);
        $this->assertEquals('client', $user->role);
        $this->assertEquals(2, $user->coach_id);

        // Simulate changing role to coach and unlinking coach
        $user->role = 'coach';
        $user->coach_id = null;

        $this->assertEquals('coach', $user->role);
        $this->assertNull($user->coach_id);
    }
}
