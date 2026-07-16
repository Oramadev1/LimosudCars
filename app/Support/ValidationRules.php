<?php

namespace App\Support;

class ValidationRules
{
    public const PHONE = 'regex:/^\+?[\d\s\-]{7,20}$/';

    public const PERSON_NAME = 'regex:/^[\p{L}\s\'\-.]+$/u';
}
