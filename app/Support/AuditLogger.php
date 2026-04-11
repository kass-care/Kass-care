<?php

namespace App\Support;

use App\Models\Activity;

class AuditLogger
{
    public static function log(
        string $action,
        ?string $description = null,
        ?string $targetType = null,
        ?int $targetId = null,
        ?int $clientId = null,
        ?string $clientName = null,
        array $meta = []
    ): void {
        $user = auth()->user();
        $request = request();

        Activity::create([
            'user_id'     => $user?->id,
            'user_name'   => $user?->name,
            'user_role'   => $user?->role,
            'action'      => $action,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'client_id'   => $clientId,
            'client_name' => $clientName,
            'description' => $description,
            'meta'        => $meta,
            'ip_address'  => $request?->ip(),
            'user_agent'  => $request?->userAgent(),
        ]);
    }
}
