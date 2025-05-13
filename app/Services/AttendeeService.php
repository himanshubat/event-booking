<?php

namespace App\Services;

use App\Models\Attendee;

class AttendeeService extends BaseService
{
    public function __construct(Attendee $attendee)
    {
        parent::__construct($attendee);
    }
}
