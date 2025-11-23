<?php

namespace Modules\TaskManagement\Enums;

enum TaskLabel: string
{
    case  ERROR = 'error'; 
    case  ISSUE = 'issue';
    case  IMPROVEMENT = 'improvement';
}
