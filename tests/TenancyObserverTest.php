<?php

namespace Sbine\Tenancy\Tests;

use Sbine\Tenancy\SuperAdmin;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\Stubs\Model;
use Sbine\Tenancy\Tests\Stubs\User;
use Sbine\Tenancy\Tests\TestCase;

class TenancyObserverTest extends TestCase
{
    /** @test */
    public function it_sets_model_tenant_on_save()
    {
        $this->app->singleton(Tenant::class, function () {
            return new Tenant(new User(['id' => 101]));
        });

        $model = new Model;
        unset($model->user_id);
        $this->assertNull($model->user_id);

        // Fire the event rather than calling save() to avoid hitting the database
        event('eloquent.saving: ' . Model::class, $model);

        $this->assertEquals(101, $model->user_id);
    }

    /** @test */
    public function it_doesnt_set_model_tenant_when_tenant_can_override()
    {
        $this->app->singleton(Tenant::class, SuperAdmin::class);

        $model = new Model;

        event('eloquent.saving: ' . Model::class, $model);

        $this->assertNull($model->user_id);
    }
}
