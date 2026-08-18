<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreRoleRequest;
use App\Http\Requests\Staff\UpdateRoleRequest;
use App\Http\Resources\Staff\RoleResource;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $roles = Role::where('pharmacy_id', $request->user()->pharmacy_id)
            ->latest()
            ->paginate(20);

        return RoleResource::collection($roles);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = Role::create([
            'pharmacy_id' => $request->user()->pharmacy_id,
            'name' => $request->string('name')->toString(),
            'slug' => Str::slug($request->string('name')->toString()),
            'description' => $request->string('description')->toString(),
            'permissions' => $request->array('permissions'),
            'is_system' => false,
        ]);

        return response()->json(new RoleResource($role), 201);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $role->update([
            'name' => $request->string('name')->toString(),
            'slug' => Str::slug($request->string('name')->toString()),
            'description' => $request->string('description')->toString(),
            'permissions' => $request->array('permissions'),
        ]);

        return response()->json(new RoleResource($role));
    }

    public function destroy(Role $role): JsonResponse
    {
        if ($role->is_system) {
            return response()->json(['message' => 'System roles cannot be deleted.'], 422);
        }

        $role->delete();

        return response()->json(['message' => 'Role deleted.']);
    }
}