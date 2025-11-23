<?php

namespace Modules\TaskManagement\Enums;

enum IssueSource: string
{
    case LPC_AR = 'lpc_ar';
    case LPC_EN = 'lpc_en';
    case CRM = 'crm';
    case LMS = 'lms';
    case LMA = 'lma';
    case REGENT = 'regent';
}
