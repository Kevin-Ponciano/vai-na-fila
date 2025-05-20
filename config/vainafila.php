<?php

return [
    'queue_priority_quota' => env('QUEUE_PRIORITY_QUOTA', 2),
    'ticket_expiration_time' => env('TICKET_EXPIRATION_TIME', 3), // minutes
    'ticket_expiration_validation_time' => env('TICKET_EXPIRATION_VALIDATION_TIME', 5), // minutes
    'qrcode_expiration_time' => env('QRCODE_EXPIRATION_TIME', 5), // minutes
];
