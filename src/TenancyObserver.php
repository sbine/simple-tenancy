<?php

namespace Sbine\Tenancy;

use Illuminate\Database\Eloquent\Model;

class TenancyObserver
{
    public function saving(Model $model)
    {
        $tenant = resolve(Tenant::class);

        if (! $tenant->canOverride()) {
            $model->setAttribute($tenant->column(), $tenant->id());
        }
    }
}
