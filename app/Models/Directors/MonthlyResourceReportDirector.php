<?php

namespace App\Models\Directors;

use App\Dto\ReportFileDto;
use App\Models\Builders\ReportBuilder;

final class MonthlyResourceReportDirector
{
    public function __construct(
        protected ReportBuilder $builder
    ) {
    }

    public function build(array $data): ReportFileDto
    {
        $reportFileName = 'report_resource.xlsx';
        $reportFilePath = 'reports/' . $reportFileName;
        $fileContent = $this->builder->createFile($reportFilePath)
            ->createWorksheet()
            ->setWorksheetTitle('1 лист')
            ->setDataWithHeaders($data)
            ->makeXlsx();

        return new ReportFileDto(fileContent: $fileContent, fileName: $reportFileName);
    }
}
