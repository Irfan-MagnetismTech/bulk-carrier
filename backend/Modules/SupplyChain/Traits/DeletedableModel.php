<?php

namespace Modules\SupplyChain\Traits;

use ReflectionClass;
use Illuminate\Database\Eloquent\Relations\Relation;

trait DeletedableModel
{
    public function preventDeletionIfRelated()
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
        
        $modelNames = implode(', and ', array_slice($models, 0, -1)) . ' and ' . end($models);

        return [
            "message" => "Data could not be deleted!",
            "errors" => [
                "id" => ["Data could not be deleted! It has references in the {$modelNames} table."]
            ]
        ];
    }

    private function getRelationMethods()
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

    private function isRelationMethod($method)
    {
        $expectedTypes = [
            'Illuminate\Database\Eloquent\Relations\HasOne',
            'Illuminate\Database\Eloquent\Relations\HasMany',
            'Illuminate\Database\Eloquent\Relations\MorphMany',
            'Illuminate\Database\Eloquent\Relations\MorphOne'
        ];

        return $method->hasReturnType() && in_array($method->getReturnType()->getName(), $expectedTypes);
    }
}
