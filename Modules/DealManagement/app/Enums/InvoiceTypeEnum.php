<?php

namespace Modules\DealManagement\Enums;

use BenSampo\Enum\Enum;

final class InvoiceTypeEnum extends Enum
{
    const standard = 'standard';
    const without_discount = 'without_discount';
}
