<?php

namespace App\Filament\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesResourceAccess
{
    public static function canAccess(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canViewAny(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canCreate(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canEdit(Model $record): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canDelete(Model $record): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canDeleteAny(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canForceDeleteAny(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canRestore(Model $record): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canRestoreAny(): bool
    {
        return static::currentUserCanAccessResource();
    }

    public static function canView(Model $record): bool
    {
        return static::currentUserCanAccessResource();
    }

    protected static function currentUserCanAccessResource(): bool
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        $permission = static::permissionNameForResource();

        return $permission !== null && $user->can($permission);
    }

    protected static function permissionNameForResource(): ?string
    {
        $resourceName = class_basename(static::class);

        $permissionMap = [
            'ActivityLogResource' => 'manage_admin',
            'AffiliateClickResource' => 'manage_crm',
            'AffiliateCommissionResource' => 'manage_finance',
            'AffiliateLinkResource' => 'manage_crm',
            'AffiliatePartnerResource' => 'manage_crm',
            'AssessmentResource' => 'manage_progress',
            'AssessmentResultResource' => 'manage_progress',
            'AttendanceRecordResource' => 'manage_learning',
            'BranchResource' => 'manage_admin',
            'CertificateResource' => 'manage_progress',
            'ClassGroupResource' => 'manage_learning',
            'ClassSessionResource' => 'manage_learning',
            'ConsultationActivityResource' => 'manage_crm',
            'ContentCategoryResource' => 'manage_content',
            'ContentItemResource' => 'manage_content',
            'CourseModuleResource' => 'manage_learning',
            'CourseResource' => 'manage_learning',
            'CustomerAccountResource' => 'manage_finance',
            'EnrollmentResource' => 'manage_learning',
            'GuardianRelationResource' => 'manage_learning',
            'InvoiceResource' => 'manage_finance',
            'LeadAssignmentResource' => 'manage_crm',
            'LeadResource' => 'manage_crm',
            'LeadSourceResource' => 'manage_crm',
            'NotificationResource' => 'manage_content',
            'OrderItemResource' => 'manage_finance',
            'OrderResource' => 'manage_finance',
            'OrganizationResource' => 'manage_admin',
            'PaymentResource' => 'manage_finance',
            'PersonResource' => 'manage_admin',
            'ProgressReportResource' => 'manage_progress',
            'ReceiptResource' => 'manage_finance',
            'ReceivableResource' => 'manage_finance',
            'RoleResource' => 'manage_admin',
            'StudentProfileResource' => 'manage_learning',
            'TeacherCommentResource' => 'manage_progress',
            'TeacherProfileResource' => 'manage_learning',
            'TrialRegistrationResource' => 'manage_crm',
            'UserResource' => 'manage_admin',
            'VideoLessonResource' => 'manage_content',
        ];

        return $permissionMap[$resourceName] ?? 'manage_admin';
    }
}
