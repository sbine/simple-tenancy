<?php

namespace Sbine\Tenancy;

use Closure;
use Illuminate\Database\Eloquent\Model;

class Tenant
{
    private $canOverride;
    private $user;

    public function __construct(?Model $user, ?Closure $canOverride = null)
    {
        $this->user = $user;
        $this->canOverride = $canOverride;
    }

    /**
     * Determine whether or not the tenant can override tenancy.
     */
    public function canOverride(): bool
    {
        return $this->canOverride
            ? call_user_func($this->canOverride, $this->user())
            : false;
    }

    /**
     * Retrieve the column on each model identifying the tenant.
     */
    public function column(): string
    {
        return 'user_' . (optional($this->user())->getKeyName() ?? 'id');
    }

    /**
     * Retrieve the current tenant's identifier.
     */
    public function id()
    {
        return optional($this->user())->getKey();
    }

    protected function user(): ?Model
    {
        return $this->user;
    }
}
