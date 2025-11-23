<?php

namespace Modules\TaskManagement\Enums;

enum TaskPriority: string
{
    case MINOR = 'minor';
    case MAJOR = 'major';
    case CRITICAL = 'critical';
    case BLOCKER = 'blocker';
}
