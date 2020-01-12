<?php

namespace Sbine\Tenancy;

class SuperAdmin extends Tenant
{
    public function __construct()
    {
    }

    public function canOverride(): bool
    {
        return true;
    }
}
