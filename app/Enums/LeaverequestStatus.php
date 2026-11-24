<?php

namespace App\Enums;

enum LeaverequestStatus:string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
