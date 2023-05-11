<?php

namespace Uneca\Scaffold\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidShapefileSet implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (count($value) < 3) {
            $fail('All three shapefile component files are required');
        }
        if (count($value) > 3) {
            $fail('Only the three shapefile component files are required');
        }

        $fileNames = collect(array_map(function ($file) {
            return $file->getClientOriginalName();
        }, $value));
        $haveSameFilenames = $fileNames->map(function ($file) {
            return str($file)->beforeLast('.')->toString();
        })->countBy()->values()->contains(3);

        if (! $haveSameFilenames) {
            $fail('All three files need to have the same filename');
        }

        $requiredComponentsIncluded = collect(['shp', 'shx', 'dbf'])
            ->diff($fileNames->map(function ($file) {
                return str($file)->afterLast('.');
            }))
            ->isNotEmpty();

        if ($requiredComponentsIncluded) {
            $fail('All three shapefile component files (shp, shx & dbf) are required');
        }
    }
}
