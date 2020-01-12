<?php

namespace Sbine\Tenancy\Tests\Stubs;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Sbine\Tenancy\HasTenancy;

class Model extends Eloquent
{
    use HasTenancy;
}
