<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Log user actions for security audit
     */
    public static function log(
        string $action,
        string $entityType,
        ?int $entityId = null,
        array $changes = [],
        ?string $description = null
    ): void {
        $user = Auth::user();
        $logData = [
            'timestamp' => now()->toIso8601String(),
            'user_id' => $user?->id ?? 'anonymous',
            'user_role' => $user?->role ?? 'guest',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'changes' => $changes,
            'description' => $description,
            'route' => request()->route()?->getName(),
            'method' => request()->method(),
        ];

        Log::channel('audit')->info('Audit Log', $logData);
    }

    /**
     * Log authentication attempts
     */
    public static function logAuthAttempt(string $username, bool $success): void
    {
        $logData = [
            'timestamp' => now()->toIso8601String(),
            'username' => $username,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'success' => $success,
            'type' => 'login_attempt',
        ];

        Log::channel('audit')->warning('Authentication Attempt', $logData);
    }

    /**
     * Log security events
     */
    public static function logSecurityEvent(string $event, array $details = []): void
    {
        $user = Auth::user();
        $logData = array_merge([
            'timestamp' => now()->toIso8601String(),
            'user_id' => $user?->id ?? 'anonymous',
            'ip_address' => request()->ip(),
            'event' => $event,
        ], $details);

        Log::channel('audit')->warning('Security Event', $logData);
    }
}
