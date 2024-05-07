<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'resource_data', required: ['id'])]
final class ResourceDataDto
{
    #[OA\Property()]
    public int $id;

    #[OA\Property(description: 'Идентификатор месячного ресурсного плана')]
    public int $resource_id;

    #[OA\Property(description: 'Название площадки', readOnly: true)]
    public ?string $location_name;

    #[OA\Property(description: 'Идентификатор площадки')]
    public int $location_id;

    #[OA\Property(description: 'Название типа площадки', readOnly: true)]
    public ?string $location_type_name;

    #[OA\Property(description: 'Название проекта', readOnly: true)]
    public ?string $project_name;

    #[OA\Property(description: 'Идентификатор проекта')]
    public int $project_id;

    #[OA\Property(description: 'Комментарий')]
    public ?string $comments;

    #[OA\Property(description: 'Приоритет', readOnly: true)]
    public ?string $recruitment_priority;

    #[OA\Property(description: 'Идентификатор приоритета')]
    public ?int $recruitment_priority_id;

    private function __construct()
    {
    }

    public static function createFromModel(
        array $attributes,
        ?string $locationName = null,
        ?string $projectName = null,
        ?string $locationTypeName = null,
        ?string $priorityTypeName = null
    ): self {
        $dto = new self();
        foreach ($attributes as $attributeKey => $attributeValue) {
            $dto->$attributeKey = $attributeValue;
        }
        $dto->location_name = $locationName;
        $dto->project_name = $projectName;
        $dto->location_type_name = $locationTypeName;
        $dto->recruitment_priority = $priorityTypeName;

        return $dto;
    }
}
