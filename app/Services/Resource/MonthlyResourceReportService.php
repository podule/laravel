<?php

namespace App\Services\Resource;

use App\Dto\ProjectResourceDataDto;
use App\Dto\ReportFileDto;
use App\Dto\ResourceDataDto;
use App\Dto\ResourceReportDataInterface;
use App\Models\Directors\MonthlyResourceReportDirector;
use App\Models\Mappers\ResourceDataMapper;
use App\Models\ResourceData;
use App\Models\Repositories\ResourceDataRepository;
use App\Models\Repositories\ResourceRepository;

final class MonthlyResourceReportService
{
    public function __construct(
        protected MonthlyResourceReportDirector $director,
        protected ResourceDataRepository $resourceDataRepository,
        protected ResourceRepository $resourceRepository,
        protected ResourceDataMapper $resourcePlanMapper,
    ) {
    }

    /**
     * @param int $resourcePlanId
     * @return ReportFileDto
     */
    public function get(
        int $resourcePlanId,
    ): ReportFileDto {
        $reportData = $this->getReportData($resourcePlanId);

        return $this->director->build($reportData);
    }

    /**
     * @param int $resourceId
     * @return array
     */
    protected function getReportData(int $resourceId): array
    {
        $resource = $this->resourceRepository->findById($resourceId);
        $data = $this->resourceDataRepository->findByResourceId(resourceId: $resourceId);
        $locationList = $this->locationsRepository->findAllArrayWithKeyId();
        $projectList = $this->projectsRepository->findAllArrayWithKeyId();

        $reportData = $this->getHeaderData();
        $dataDto = [];

        $currentProjectId = null;

        /** @var ResourceData $plan */
        foreach ($data as $datum) {
            if (!is_null($currentProjectId) && $currentProjectId !== $datum->project_id) {
                $projectDto = new ProjectResourceDataDto(
                    project_id: $currentProjectId,
                    project_name: $projectList->getProject($currentProjectId)?->name ?? '',
                    resource_data: $dataDto
                );
                $reportData[] = $this->getReportDataItem($projectDto);
                foreach ($dataDto as $item) {
                    $reportData[] = $this->getReportDataItem($item);
                }
                $dataDto = [];
            }
            $currentProjectId = $datum->project_id;
            $attributes = $this->resourcePlanMapper->toDtoAttribute(model: $datum);
            $dto = ResourceDataDto::createFromModel(
                attributes: $attributes,
                locationName: $locationList->getLocation($datum->location_id)?->name,
                projectName: $projectList->getProject($datum->project_id)?->name,
                locationTypeName: $locationList->getLocation($datum->location_id)?->type_name,
                priorityTypeName: $datum->priorityType?->title
            );

            $dataDto[] = $dto;
        }

        return $reportData;
    }

    /**
     * @return array
     */
    protected function getHeaderData(): array
    {
        $reportDataItem = $this->getReportDataItem(null);

        return array_keys($reportDataItem);
    }

    /**
     * @param ResourceReportDataInterface|null $resourcePlanData
     * @return array
     */
    protected function getReportDataItem(?ResourceReportDataInterface $resourcePlanData): array
    {
        return [
            'Площадка' => $resourcePlanData?->getLocationName(),
            'Тип' => $resourcePlanData?->getLocationTypeName(),
            'Проект' => $resourcePlanData?->getProjectName(),
            'Комментарий' => $resourcePlanData?->getComment(),
            'Приоритет набора' => $resourcePlanData?->getPriorityName(),
        ];
    }
}
