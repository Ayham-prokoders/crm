<?php

namespace Modules\Lms\Enums;

use BenSampo\Enum\Enum;

final class SessionStatusEnum extends Enum
{
    const scheduled = 'scheduled';
    const ongoing = 'ongoing';
    const cancelled = 'cancelled';
    const completed = 'completed';
}
