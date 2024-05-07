<?php

namespace App\Models\Builders;

use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

final class ReportBuilder
{
    protected string $filePath;
    protected Spreadsheet $spreadsheet;
    protected Worksheet $activeWorksheet;

    public function createFile(string $filePath): self
    {
        $this->filePath = $filePath;
        $this->spreadsheet = new Spreadsheet();

        return $this;
    }

    public function createWorksheet(): self
    {
        $this->activeWorksheet = $this->spreadsheet->getActiveSheet();

        return $this;
    }

    public function setWorksheetTitle(string $title): self
    {
        $this->activeWorksheet->setTitle($title);

        return $this;
    }

    public function setDataWithHeaders(array $data): self
    {
        $this->activeWorksheet->fromArray($data, null, 'A1', true);

        // объединение одинаковых заголовков в шапке файла отчета
        $prevHeader = null;
        $isMerge = false;
        $startPositionMerge = 1;
        foreach ($data[0] as $key => $column) {
            if (!is_null($prevHeader) && !$isMerge && $prevHeader === $column) {
                $isMerge = true;
                $startPositionMerge = $key;
            }

            if ($isMerge && $prevHeader !== $column) {
                $this->activeWorksheet->mergeCells([$startPositionMerge, 1, $key, 1]);
                $isMerge = false;
            }
            $prevHeader = $column;
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function makeXlsx(): string
    {
        File::delete($this->filePath);
        $writer = new Xlsx($this->spreadsheet);
        $writer->save($this->filePath);

        return File::get($this->filePath);
    }
}
