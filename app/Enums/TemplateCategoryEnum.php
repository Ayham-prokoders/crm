<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TemplateCategoryEnum extends Enum
{
    const payments= 'Payments';
    const individual = 'Individual';
    const questionnaires = 'Questionnaires';
    const registration_process = 'Registration process';
    const registration_details = 'Registration Details';

}
