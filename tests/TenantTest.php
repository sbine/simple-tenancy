<?php

namespace Sbine\Tenancy\Tests;

use PHPUnit\Framework\Attributes\Test;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\Stubs\User;
use Sbine\Tenancy\Tests\TestCase;

class TenantTest extends TestCase
{
    #[Test]
    public function it_returns_the_tenant_id()
    {
        $user = new User(['id' => 31]);

        $this->assertEquals(31, (new Tenant($user))->id());
    }

    #[Test]
    public function it_returns_user_id_as_the_default_column()
    {
        $user = new User;

        $this->assertEquals('user_id', (new Tenant($user))->column());
    }

    #[Test]
    public function it_appends_the_custom_primary_key_if_one_is_set()
    {
        $user = new User;
        $user->setKeyName('hashid');

        $this->assertEquals('user_hashid', (new Tenant($user))->column());
    }

    #[Test]
    public function it_doesnt_allow_override_by_default()
    {
        $tenant = new Tenant(new User);

        $this->assertFalse($tenant->canOverride());
    }

    #[Test]
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
