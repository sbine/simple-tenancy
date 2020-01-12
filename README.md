# Simple Laravel Multi-Tenancy

![Build status](https://img.shields.io/github/workflow/status/sbine/simple-tenancy/Run%20tests)

Simple Tenancy adds automatic multi-tenant support to Eloquent models stored in a shared database.

It requires zero configuration in most cases, relying on established Laravel conventions and a single column on each table.

### How it works
Under the hood, it has only 4 components:
1. `Tenant`: Keeps track of the current user
2. `HasTenancy`: A trait for models belonging to the tenant, which registers:
3. `TenancyScope`: A [global scope](https://laravel.com/docs/master/eloquent#global-scopes) limiting all the model's queries to the current tenant
4. `TenancyObserver`: An [observer](https://laravel.com/docs/master/eloquent#observers) which sets the current tenant column/identifier on save

By default, the tenant is Laravel's `Auth::user()`, and all tenancy checks are disabled when no one is authenticated.

## Installation
1. Install using Composer:
```bash
composer require sbine/simple-tenancy
```

2. Add the `HasTenancy` trait to all models belonging to the tenant:
```php
class Account extends Model
{
    use \Sbine\Tenancy\HasTenancy;
}
```

3. Ensure a `user_id` column exists on the table of every model using the trait.

## Customizing the tenant column/ID
If needed, you can customize the name of the tenant column or identifier by extending the Tenant class and [binding it into the container](#customizing-tenancy-behavior):
```php
class MyTenant extends \Sbine\Tenancy\Tenant
{
    /**
     * Retrieve the column identifying each model's tenant.
     */
    public function column()
    {
        return 'tenant_hashid';
    }

    /**
     * Retrieve the current tenant's identifier.
     */
    public function id()
    {
        return $this->user->hashid;
    }
}
```

## Customizing tenancy behavior
By default, if no user is authenticated tenancy will be completely disabled. This is by design so the library doesn't interfere with testing, seeding, and other unauthenticated model usage.

To change the tenancy behavior in any way, you can override the Tenant binding in the application container. 

For example, to prevent all querying and saving of tenant models when no one is authenticated:
```php
    // In AppServiceProvider.php
    public function register()
    {
        $this->app->singleton(\Sbine\Tenancy\Tenant::class, function () {
            // Throw an AuthenticationException if auth check fails
            return new \Sbine\Tenancy\Tenant(Auth::authenticate());
        });
    }
```

To allow overriding tenancy checks based on a policy or method, pass a callback returning `true` or `false`:
```php
    // In AppServiceProvider.php
    public function register()
    {
        $this->app->singleton(\Sbine\Tenancy\Tenant::class, function () {
            return new \Sbine\Tenancy\Tenant(Auth::user(), function ($user) {
                return $user->can('admin');
            });
        });
    }
```

For complete control over tenant behavior, you can bind your own Tenant implementation:
```php
    // In AppServiceProvider.php
    public function register()
    {
        $this->app->singleton(\Sbine\Tenancy\Tenant::class, function () {
            return new MyTenant;
        });
    }
```

## Disabling tenancy
For convenience, a SuperAdmin class is provided which you can bind at any time to disable tenant checks:
```php
    $this->app->singleton(\Sbine\Tenancy\Tenant::class, \Sbine\Tenancy\SuperAdmin::class);
```
