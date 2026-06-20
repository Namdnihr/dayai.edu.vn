<?php

namespace Tests\Feature;

use App\Filament\Pages\OperationalDashboard;
use App\Filament\Pages\BiReports;
use App\Filament\Pages\RevenueReport;
use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\ProgressReports\ProgressReportResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class FilamentPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_sales_can_manage_crm_but_not_finance(): void
    {
        $user = $this->createUserWithRole('sales', ['view_dashboard', 'manage_crm', 'manage_content']);

        $this->actingAs($user);

        $this->assertTrue(LeadResource::canViewAny());
        $this->assertFalse(OrderResource::canViewAny());
        $this->assertTrue(OperationalDashboard::canAccess());
        $this->assertTrue(BiReports::canAccess());
        $this->assertFalse(RevenueReport::canAccess());
    }

    public function test_accountant_can_manage_finance_but_not_crm(): void
    {
        $user = $this->createUserWithRole('accountant', ['view_dashboard', 'manage_finance']);

        $this->actingAs($user);

        $this->assertTrue(OrderResource::canViewAny());
        $this->assertFalse(LeadResource::canViewAny());
        $this->assertTrue(RevenueReport::canAccess());
        $this->assertTrue(BiReports::canAccess());
    }

    public function test_teacher_can_manage_progress_but_not_finance(): void
    {
        $user = $this->createUserWithRole('teacher', ['view_dashboard', 'manage_learning', 'manage_progress']);

        $this->actingAs($user);

        $this->assertTrue(ProgressReportResource::canViewAny());
        $this->assertFalse(OrderResource::canViewAny());
        $this->assertTrue(OperationalDashboard::canAccess());
        $this->assertTrue(BiReports::canAccess());
    }

    public function test_admin_can_access_all_resource_groups(): void
    {
        $user = $this->createUserWithRole('admin', []);

        $this->actingAs($user);

        $this->assertTrue(LeadResource::canViewAny());
        $this->assertTrue(OrderResource::canViewAny());
        $this->assertTrue(ProgressReportResource::canViewAny());
        $this->assertTrue(RevenueReport::canAccess());
        $this->assertTrue(BiReports::canAccess());
    }

    /**
     * @param  array<int, string>  $permissions
     */
    private function createUserWithRole(string $roleName, array $permissions): User
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach ($permissions as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        $role = Role::query()->firstOrCreate([
            'name' => $roleName,
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($permissions);

        $user = User::factory()->create([
            'status' => 'active',
        ]);

        $user->assignRole($role);

        return $user;
    }
}
