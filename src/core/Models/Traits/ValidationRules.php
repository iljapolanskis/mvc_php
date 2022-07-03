<?php

namespace MVC\Core\Models\Traits;

interface ValidationRules
{
    public const RULE_REQUIRED = 'required';
    public const RULE_MATCH = 'match';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_EMAIL = 'email';
    public const RULE_UNIQUE = 'unique';
}