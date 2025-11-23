<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class QuestionEnum extends Enum
{
    const one_choice= 'one_choice';
    const multi_choice = 'multi_choice';
    const text = 'text';
    const rating = 'rating';
    const missing_word='missing_word';

}
