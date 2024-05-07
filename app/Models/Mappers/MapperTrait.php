<?php

namespace App\Models\Mappers;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionException;

trait MapperTrait
{
    /**
     * @throws ReflectionException
     */
    public function mapToModel(array $attributesMap, mixed $dto, bool $initializeAllProperties = false): array
    {
        $attributes = [];

        foreach ($attributesMap as $modelAttribute => $dtoAttribute) {
            if ($initializeAllProperties || (new ReflectionClass(get_class($dto)))
                    ->getProperty($dtoAttribute)
                    ->isInitialized($dto)) {
                $attributes[$modelAttribute] = $dto->$dtoAttribute;
            }
        }

        return $attributes;
    }

    public function mapToDtoAttribute(array $attributesMap, Model $model): array
    {
        $attributes = [];

        foreach ($attributesMap as $modelAttribute => $dtoAttribute) {
            $attributes[$dtoAttribute] = $model->$modelAttribute;
        }

        return $attributes;
    }
}
