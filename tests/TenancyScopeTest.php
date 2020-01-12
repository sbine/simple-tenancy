<?php

namespace Sbine\Tenancy\Tests;

use Illuminate\Database\Eloquent\Builder;
use Sbine\Tenancy\SuperAdmin;
use Sbine\Tenancy\TenancyScope;
use Sbine\Tenancy\Tenant;
use Sbine\Tenancy\Tests\Stubs\Model;
use Sbine\Tenancy\Tests\Stubs\User;
use Sbine\Tenancy\Tests\TestCase;

class TenancyScopeTest extends TestCase
{
    /** @test */
    public function it_applies_a_where_condition_to_the_builder()
    {
        $this->app->singleton(Tenant::class, function () {
            return new Tenant(new User(['id' => 50]));
        });
        $builder = resolve(Builder::class);

        (new TenancyScope)->apply($builder, (new Model));

        $where = $builder->getQuery()->wheres[0];

        $this->assertEquals($where['column'], 'models.user_id');
        $this->assertEquals($where['value'], 50);
    }

    /** @test */
    public function it_doesnt_apply_a_where_condition_when_tenant_can_override()
    {
        $this->app->singleton(Tenant::class, SuperAdmin::class);
        $builder = resolve(Builder::class);

        (new TenancyScope)->apply($builder, (new Model));

        $this->assertEmpty($builder->getQuery()->wheres);
    }
}
