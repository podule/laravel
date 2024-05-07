<?php

namespace App\Dto;

final class ReportFileDto
{
    public function __construct(
        public string $fileContent,
        public string $fileName,
    ) {
    }
}
