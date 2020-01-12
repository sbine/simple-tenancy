<?php

namespace Sbine\Tenancy;

trait HasTenancy
{
    /**
     * Apply the tenancy scope and observer in the model's boot method.
     */
    public static function bootHasTenancy()
    {
        static::addGlobalScope(new TenancyScope);
        static::observe(new TenancyObserver);
    }
}
