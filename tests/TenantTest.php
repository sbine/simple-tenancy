<?php

namespace Sbine\Tenancy\Tests;

use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\Stubs\User;
use Sbine\Tenancy\Tests\TestCase;

class TenantTest extends TestCase
{
    /** @test */
    public function it_returns_the_tenant_id()
    {
        $user = new User(['id' => 31]);

        $this->assertEquals(31, (new Tenant($user))->id());
    }

    /** @test */
    public function it_returns_user_id_as_the_default_column()
    {
        $user = new User;

        $this->assertEquals('user_id', (new Tenant($user))->column());
    }

    /** @test */
    public function it_appends_the_custom_primary_key_if_one_is_set()
    {
        $user = new User;
        $user->setKeyName('hashid');

        $this->assertEquals('user_hashid', (new Tenant($user))->column());
    }

    /** @test */
    public function it_doesnt_allow_override_by_default()
    {
        $tenant = new Tenant(new User);

        $this->assertFalse($tenant->canOverride());
    }

    /** @test */
    public function it_returns_the_results_of_the_override_check_if_provided()
    {
        $tenant = new Tenant(new User, function () {
            return false;
        });

        $this->assertFalse($tenant->canOverride());

        $tenant = new Tenant(new User, function () {
            return true;
        });

        $this->assertTrue($tenant->canOverride());
    }
}
