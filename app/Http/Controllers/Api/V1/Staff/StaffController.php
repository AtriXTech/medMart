<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Enums\StaffRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreStaffRequest;
use App\Http\Requests\Staff\UpdateStaffRequest;
use App\Http\Resources\Staff\StaffResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $staff = User::where('pharmacy_id', $request->user()->pharmacy_id)
            ->with('staffRole')
            ->where(function ($query) {
                $query->where('role', StaffRole::Pharmacist)
                    ->orWhere('role', StaffRole::InventoryManager)
                    ->orWhere('role', StaffRole::Cashier);
            })
            ->latest()
            ->paginate(20);

        return StaffResource::collection($staff);
    }

    public function store(StoreStaffRequest $request): JsonResponse
    {
        $roleId = $request->integer('staff_role_id');
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json([
                'message' => 'Please select a valid role.',
                'errors' => ['staff_role_id' => ['The selected role is invalid.']],
            ], 422);
        }

        $user = User::create([
            'pharmacy_id' => $request->user()->pharmacy_id,
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'role' => $this->determineSystemRole($role),
            'status' => 'active',
            'staff_role_id' => $role->id,
        ]);

        return response()->json(new StaffResource($user->load('staffRole')), 201);
    }

    private function determineSystemRole(Role $role): StaffRole
    {
        if ($role->is_system) {
            return match ($role->slug) {
                'pharmacist' => StaffRole::Pharmacist,
                'inventory_manager' => StaffRole::InventoryManager,
                'cashier' => StaffRole::Cashier,
                default => StaffRole::Cashier,
            };
        }

        return StaffRole::Cashier;
    }

    public function update(UpdateStaffRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if ($request->filled('staff_role_id')) {
            $role = Role::find($request->integer('staff_role_id'));
            if ($role) {
                $user->staff_role_id = $role->id;
                if ($role->is_system) {
                    $user->role = $this->determineSystemRole($role);
                }
                $user->save();
            }
        }

        return response()->json(new StaffResource($user->load('staffRole')));
    }

    public function deactivate(User $user): JsonResponse
    {
        $user->update(['status' => 'inactive']);

        return response()->json(new StaffResource($user->load('staffRole')));
    }

    private function mapRoleToEnum(string $role): StaffRole
    {
        return match ($role) {
            'pharmacist' => StaffRole::Pharmacist,
            'inventory_manager' => StaffRole::InventoryManager,
            'cashier' => StaffRole::Cashier,
            default => StaffRole::Cashier,
        };
    }

    private function assignDefaultRole(User $user, StaffRole $roleEnum): void
    {
        $roleDefaults = config('permissions.role_defaults');
        $roleKey = match ($roleEnum) {
            StaffRole::Pharmacist => 'pharmacist',
            StaffRole::InventoryManager => 'inventory_manager',
            StaffRole::Cashier => 'cashier',
            default => null,
        };

        if (!$roleKey || !isset($roleDefaults[$roleKey])) {
            return;
        }

        $defaultRole = Role::firstOrCreate(
            [
                'pharmacy_id' => $user->pharmacy_id,
                'slug' => $roleKey,
            ],
            [
                'name' => ucwords(str_replace('_', ' ', $roleKey)),
                'description' => 'Default ' . str_replace('_', ' ', $roleKey) . ' role',
                'permissions' => $roleDefaults[$roleKey],
                'is_system' => true,
            ]
        );

        $user->staff_role_id = $defaultRole->id;
        $user->save();
    }
}
