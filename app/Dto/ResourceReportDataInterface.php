<?php

namespace App\Dto;


interface ResourceReportDataInterface
{
    public function getLocationName(): ?string;

    public function getLocationTypeName(): ?string;

    public function getProjectName(): ?string;

    public function getComment(): ?string;

    public function getPriorityName(): ?string;
}
