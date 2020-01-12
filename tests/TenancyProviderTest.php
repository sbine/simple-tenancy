<?php

namespace Sbine\Tenancy\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\TestCase;

/**
 * Tests the default behavior when nothing is overridden.
 */
class TenancyProviderTest extends TestCase
{
    /** @test */
    public function it_only_allows_overriding_while_no_one_is_authenticated()
    {
        $this->assertTrue(resolve(Tenant::class)->canOverride());

        Auth::login(new User);
        $this->assertFalse(resolve(Tenant::class)->canOverride());

        Auth::logout();
        $this->assertTrue(resolve(Tenant::class)->canOverride());
    }

    /** @test */
    public function it_sets_tenant_to_the_authenticated_user()
    {
        $user = new User;
        $user->id = 10;

        Auth::login($user);

        $this->assertEquals(10, resolve(Tenant::class)->id());
    }
}
