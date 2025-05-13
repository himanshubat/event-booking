<?php

namespace App\Services;

use App\Models\Event;

class EventService extends BaseService
{
    public function __construct(Event $event)
    {
        parent::__construct($event);
    }
}
