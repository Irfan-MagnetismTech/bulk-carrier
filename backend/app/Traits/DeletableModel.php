<?php

namespace App\Traits;

use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Exceptions\HttpResponseException;

trait DeletableModel
{
    /**
     * Prevents deletion of data if it is related to other models
     *
     * @return array
     */
    public function preventDeletionIfRelated(): ?array
    {
        $allMethods = $this->getRelationMethods();

        $methods = [];
        if (property_exists($this, 'skipForDeletionCheck')) {
            $methods = array_diff($allMethods, $this->skipForDeletionCheck ?? []);
        }

        $totalCount = 0;
        foreach ($methods as $method) {
            $relation = $this->{$method}();
            if ($relation instanceof Relation && $relation->count() > 0) {
                $totalCount += $relation->count();
            }
        }

        if ($totalCount > 0) {
            throw new HttpResponseException(response()->json([
                "message" => "Data could not be deleted! It has references in the {$this->features} table.",
                "errors" => [
                    "id" => ["Data is in use and cannot be deleted! It has references in the {$this->features}."]
                ]
            ], 422));
        }

        return null;
    }

    /**
     * Retrieves an array of all the relation methods in the class
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
     * Checks if a given method is a relation method
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
