<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = Permission::query()
            ->orderBy('module')
            ->orderBy('slug')
            ->get();

        return PermissionResource::collection($permissions);
    }
}
