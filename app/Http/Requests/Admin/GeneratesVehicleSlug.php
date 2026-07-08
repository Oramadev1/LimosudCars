<?php

namespace App\Http\Requests\Admin;

use App\Models\Vehicle;
use Illuminate\Support\Str;

trait GeneratesVehicleSlug
{
    protected function uniqueVehicleSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'vehicle';
        }

        $slug = $base;
        $suffix = 2;

        while (Vehicle::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
