<?php

namespace App\Models\Repositories;

use App\Models\ResourcePlanData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

final class ResourceDataRepository
{

    public function findByResourceId(
        int $resourceId,
        ?array $locationIds = null,
        ?array $projectIds = null,
        ?array $priorityIds = null
    ): Collection {
        return ResourceData::where('resource_id', '=', $resourceId)
            ->when($locationIds, function (Builder $query, array $locationIds) {
                $query->whereIn('location_id', $locationIds);
            })
            ->when($projectIds, function (Builder $query, array $projectIds) {
                $query->whereIn('project_id', $projectIds);
            })
            ->when($priorityIds, function (Builder $query, array $priorityIds) {
                $query->whereIn('priority_id', $priorityIds);
            })
            ->orderBy('project_id', 'asc')
            ->orderBy('location_id', 'asc')
            ->get();
    }
}
