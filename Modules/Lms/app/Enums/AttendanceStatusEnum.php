<?php

namespace Modules\Lms\Enums;

use BenSampo\Enum\Enum;

final class AttendanceStatusEnum extends Enum
{
    const present = 'present';
    const absent = 'absent';
    const tardiness= 'tardiness';
}
