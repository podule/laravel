<?php

namespace App\Dto;

use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'project_resource_data', description: 'Данные сгруппированные по проекту')]
final class ProjectResourceDataDto
{
    #[OA\Property()]
    public int $project_id;

    #[OA\Property()]
    public string $project_name;

    #[OA\Property(type: 'array', items: new OA\Items(ref: '#/components/schemas/resource_data'), readOnly: true)]
    public array $children;

    /**
     * @param int $project_id
     * @param string $project_name
     * @param array $resource_data
     */
    public function __construct(
        int $project_id,
        string $project_name,
        array $resource_data
    ) {
        $this->project_id = $project_id;
        $this->project_name = $project_name;
        $this->children = $resource_data;
    }
}
