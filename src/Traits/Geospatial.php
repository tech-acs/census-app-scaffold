<?php

namespace Uneca\Scaffold\Traits;

use Uneca\Scaffold\Models\Area;

trait Geospatial
{
    private static function findContainingGeometry($level, $geom)
    {
        return Area::ofLevel($level)
            ->whereRaw("ST_Area(ST_Intersection(geom::geometry, $geom)) > 0.60 * ST_Area($geom)")
            ->first();
    }
}
