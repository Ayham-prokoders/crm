<?php

namespace Modules\TaskManagement\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case TESTING = 'testing';
    case DONE = 'done';
    case Completed = 'Completed';

}

