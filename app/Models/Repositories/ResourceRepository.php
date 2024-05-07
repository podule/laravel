<?php

namespace App\Models\Repositories;

use App\Models\Resource;

final class ResourceRepository
{
    public function findById(int $id): ?Resource
    {
        return Resource::where('id', '=', $id)->first();
    }
}
