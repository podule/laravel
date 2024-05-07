<?php

namespace App\Models\Mappers;

use App\Dto\ResourceDataDto;
use App\Models\ResourceData;
use ReflectionException;

class ResourceDataMapper
{
    use MapperTrait;

    public const ELOQUENT_TO_DTO_MAPPING = [
        'id' => 'id',
        'resource_id' => 'resource_id',
        'location_id' => 'location_id',
        'project_id' => 'project_id',
        'comments' => 'comments',
        'recruitment_priority_id' => 'recruitment_priority_id',
    ];

    /**
     * @throws ReflectionException
     */
    public function toModelAttribute(ResourceDataDto $dto, bool $initializeAllProperties = false): array
    {
        $attributesMap = self::ELOQUENT_TO_DTO_MAPPING;

        return $this->mapToModel($attributesMap, $dto, $initializeAllProperties);
    }

    public function toDtoAttribute(ResourceData $model): array
    {
        $attributesMap = self::ELOQUENT_TO_DTO_MAPPING;

        return $this->mapToDtoAttribute($attributesMap, $model);
    }
}
