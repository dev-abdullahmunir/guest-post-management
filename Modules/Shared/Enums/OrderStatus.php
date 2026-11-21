<?php

namespace Modules\Shared\Enums;

class OrderStatus
{
    // Normal Flow (8 Steps Flow from PDF)
    const PENDING = 'pending';                     // 1. Order place kiya client ne
    const ACCEPTED = 'accepted';                   // 2. Seller ne order accept kiya
    const CONTENT_SUBMITTED = 'content_submitted'; // 3. Client ne content submit kiya / writer assigned
    const PUBLISHED = 'published';                 // 4. Seller ne live link submit kiya
    const COMPLETED = 'completed';                 // 5. Client ne verify karke complete kiya

    // Exceptional Flow (Agar kuch ghalat ho jaye - PDF Page 8)
    const REJECTED = 'rejected';                   // Seller reject kare -> Client refund
    const REVISION_REQUESTED = 'revision_requested'; // Client changes maange -> wapas seller ke paas
    const DISPUTED = 'disputed';                   // Admin faisla karega

    public static function all(): array
    {
        return [
            self::PENDING,
            self::ACCEPTED,
            self::CONTENT_SUBMITTED,
            self::PUBLISHED,
            self::COMPLETED,
            self::REJECTED,
            self::REVISION_REQUESTED,
            self::DISPUTED,
        ];
    }
}
