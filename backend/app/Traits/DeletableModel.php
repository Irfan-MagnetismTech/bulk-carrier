<?php

namespace App\Traits;

use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;

trait DeletableModel
{
    /**
     * Prevents deletion of data if it is related to other models.
     *
     * @return array
     */
    public function preventDeletionIfRelated(): array
    {
        $methods = $this->getRelationMethods();

        $models = [];
        foreach ($methods as $method) {
            $relation = $this->{$method}();

            if ($relation instanceof Relation && $relation->count() > 0) {
                $finalModelName = implode(' ', preg_split('/(?=[A-Z])/', class_basename($relation->getRelated()), -1, PREG_SPLIT_NO_EMPTY));

                $models[] = $finalModelName;
            }
        }

        $modelNames = count($models) > 1 ? implode(', ', array_slice($models, 0, -1)) . ' and ' . end($models) : implode('', $models);

        return [
            "message" => "Data could not be deleted! It has references in the {$modelNames} table.",
            "errors" => [
                "id" => ["Data is in use and cannot be deleted!"]
            ]
        ];
    }

    /**
     * Retrieves an array of all the relation methods in the class.
     *
     * @return array
     */
    private function getRelationMethods(): array
    {
        $reflection = new ReflectionClass($this);
        $methods = [];

        foreach ($reflection->getMethods() as $method) {
            if ($this->isRelationMethod($method)) {
                $methods[] = $method->name;
            }
        }

        return $methods;
    }

    /**
     * Checks if a given method is a relation method.
     *
     * @param mixed
     * @return bool
     */
    private function isRelationMethod($method): bool
    {
        $expectedTypes = [
            'Illuminate\Database\Eloquent\Relations\HasOne',
            'Illuminate\Database\Eloquent\Relations\HasMany',
            'Illuminate\Database\Eloquent\Relations\MorphMany',
            'Illuminate\Database\Eloquent\Relations\MorphOne',
            'Illuminate\Database\Eloquent\Relations\HasManyThrough',
        ];

        return $method->hasReturnType() && in_array($method->getReturnType()->getName(), $expectedTypes);
    }
}