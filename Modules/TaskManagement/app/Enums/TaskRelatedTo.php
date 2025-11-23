<?php

namespace Modules\TaskManagement\Enums;

enum TaskRelatedTo: string
{
    case EMAILS = 'emails';
    case DASHBOARD = 'dashboard';
    case WEBSITE = 'website';
}
