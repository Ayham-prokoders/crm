<?php

namespace Modules\TaskManagement\Enums;

use BenSampo\Enum\Enum;

final class TaskTypeEnum extends Enum
{
    const open = 'open';
    const inprogress = 'inprogress';
    const done = 'done';
    const pending = 'pending';
    const canceled = 'canceled';
}
