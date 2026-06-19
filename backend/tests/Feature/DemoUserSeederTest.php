<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_role_users_are_seeded_for_admin_review(): void
    {
        $this->seed(DatabaseSeeder::class);

        $expectedUsers = [
            'admin@dayai.edu.vn' => 'admin',
            'sales@dayai.edu.vn' => 'sales',
            'teacher@dayai.edu.vn' => 'teacher',
            'accountant@dayai.edu.vn' => 'accountant',
        ];

        foreach ($expectedUsers as $email => $roleName) {
            $user = User::query()->where('email', $email)->first();

            $this->assertNotNull($user, "Missing demo user {$email}");
            $this->assertSame('active', $user->status);
            $this->assertTrue($user->hasRole($roleName));
            $this->assertTrue($user->canAccessPanel(app(Panel::class)));
        }
    }
}
