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

    /**
     * Initialize tenant column in new models.
     */
    public function initializeHasTenancy()
    {
        $tenant = resolve(Tenant::class);

        if (! $tenant->canOverride()) {
            $this->setAttribute($tenant->column(), $tenant->id());
        }
    }
}
