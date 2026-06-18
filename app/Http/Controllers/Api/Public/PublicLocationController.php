<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Public
 *
 * Public lookup endpoints for website visitors.
 */
class PublicLocationController extends Controller
{
    /**
     * List active public pickup/dropoff locations.
     *
     * @unauthenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $locations = Location::query()
            ->with('locationType')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return LocationResource::collection($locations);
    }
}
