<?php

namespace Sbine\Tenancy\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\Test;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\TestCase;

/**
 * Tests the default behavior when nothing is overridden.
 */
class TenancyProviderTest extends TestCase
{
    #[Test]
    public function it_only_allows_overriding_while_no_one_is_authenticated()
    {
        $this->assertTrue(resolve(Tenant::class)->canOverride());

        Auth::login(new User);
        $this->assertFalse(resolve(Tenant::class)->canOverride());

        Auth::logout();
        $this->assertTrue(resolve(Tenant::class)->canOverride());
    }

    #[Test]
    public function it_sets_tenant_to_the_authenticated_user()
    {
        $user = new User;
        $user->id = 10;

        Auth::login($user);

        $this->assertEquals(10, resolve(Tenant::class)->id());
    }

    #[Test]
    public function it_uses_the_user_who_is_logged_in_at_query_time()
    {
        $user = new User;
        $user->id = 10;
        Auth::login($user);

        $this->assertEquals(10, resolve(Tenant::class)->id());
    }

    #[Test]
    public function it_keeps_a_binding_made_by_the_app()
    {
        $this->app->bind(Tenant::class, function () {
            return new Tenant(new \Sbine\Tenancy\Tests\Stubs\User(['id' => 7]));
        });

        (new \Sbine\Tenancy\TenancyProvider($this->app))->register();

        $this->assertEquals(7, resolve(Tenant::class)->id());
    }
}
