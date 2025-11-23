<?php

namespace Modules\DealManagement\Enums;

use BenSampo\Enum\Enum;

final class PaymentModeEnum extends Enum
{
    const invoice_me = 'invoice_me';
    const invoice_company = 'invoice_company';
}
