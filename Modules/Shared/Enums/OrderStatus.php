<?php

namespace Modules\Shared\Enums;

class OrderStatus
{
    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const IN_PROGRESS = 'in_progress';
    const PUBLISHED = 'published';
    const REVISION_REQUESTED = 'revision_requested';
    const COMPLETED = 'completed';
    const DISPUTED = 'disputed';
    const CANCELLED = 'cancelled';

    public static function all(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::IN_PROGRESS,
            self::PUBLISHED,
            self::REVISION_REQUESTED,
            self::COMPLETED,
            self::DISPUTED,
            self::CANCELLED,
        ];
    }
}
