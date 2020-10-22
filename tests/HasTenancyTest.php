<?php

namespace Sbine\Tenancy\Tests;

use Sbine\Tenancy\SuperAdmin;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\Stubs\Model;
use Sbine\Tenancy\Tests\Stubs\User;
use Sbine\Tenancy\Tests\TestCase;

class HasTenancyTest extends TestCase
{
    /** @test */
    public function it_sets_tenant_for_new_models()
    {
        $this->app->singleton(Tenant::class, function () {
            return new Tenant(new User(['id' => 101]));
        });

        $this->assertEquals(101, (new Model)->user_id);
    }

    /** @test */
    public function it_doesnt_set_tenant_for_new_models_when_tenant_can_override()
    {
        $this->app->singleton(Tenant::class, SuperAdmin::class);

        $this->assertNull((new Model)->user_id);
    }
}
