<?php

namespace App\Http\Controllers;


use OpenApi\Attributes as OA;
use App\Services\Resource\MonthlyResourceReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;
use OpenApi\Attributes\MediaType;

class ReportController extends Controller
{

    public function __construct(
        protected MonthlyResourceReportService $resourceReportService
    ) {
    }

    #[OA\Get(path: '/api/report/monthly', description: 'Выгрузка отчета в формате Excel', tags: ['Reports'], parameters: [
        new OA\Parameter(
            name: "id", description: 'Идентификатор группы данных', in: "path", required: true, schema: new OA\Schema(
            type: 'integer'
        )
        ),
    ])]
    #[OA\Response(response: '200', description: 'Файл отчета', content: new OA\MediaType(
        mediaType: 'application/octet-stream',
    ))]
    public function monthly(Request $request): HttpResponse
    {
        $resourceId = $request->get('id');
        $reportFileDto = $this->resourceReportService->get($resourceId);
        $response = Response::make($reportFileDto->fileContent);

        $response->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->header('Content-Disposition', ('attachment; filename="' . $reportFileDto->fileName . '"'));

        return $response;
    }
}
