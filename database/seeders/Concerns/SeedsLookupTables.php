<?php

namespace Database\Seeders\Concerns;

use Illuminate\Database\Eloquent\Model;

trait SeedsLookupTables
{
    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, string>  $values
     */
    private function seedLookupValues(string $modelClass, array $values): void
    {
        foreach ($values as $slug => $name) {
            $modelClass::query()->updateOrCreate(
                ['slug' => $slug],
                ['name' => $name]
            );
        }
    }
}
