<?php

namespace App\Rules;

use Illuminate\Validation\Rule;

class UniqueChildValue implements Rule
{
    protected $parentId;

    public function __construct($parentId)
    {
        $this->parentId = $parentId;
    }

    public function passes($attribute, $value)
    {
        // Check if the value is unique for the given parent
        return !ParentModel::find($this->parentId)->children()->where('value', $value)->exists();
    }

    public function message()
    {
        return 'The value must be unique for the given parent.';
    }
}