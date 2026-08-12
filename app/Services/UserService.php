<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getUserPageData(Request $request): array
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $perPage = $request->input('per_page', 10);

        // 1. Query Utama
        $query = User::with(['roles', 'stockEntries', 'stockExits'])
            ->withCount(['stockEntries', 'stockExits']);

        // Proteksi Akun Maintenance (Sembunyikan ID 6 jika bukan dikelola oleh ID 6)
        if (auth()->id() !== 6) {
            $query->where('id', '!=', 6);
        }

        // Filter Pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($roleFilter) {
            $query->whereHas('roles', function ($rQ) use ($roleFilter) {
                $rQ->where('name', $roleFilter);
            });
        }

        $limit = $perPage === 'all' ? 10000 : (int) $perPage;
        $users = $query->latest()->paginate($limit)->appends($request->all());

        // Mini Analytics Data
        $allUsers = User::withCount(['stockEntries', 'stockExits'])->get();
        if (auth()->id() !== 6) {
            $allUsers = $allUsers->where('id', '!=', 6);
        }

        $totalUsersCount = $allUsers->count();
        $activeUsersCount = $allUsers->where('is_active', true)->count();

        // Petugas Teraktif Transaksi
        $topUser = $allUsers->sortByDesc(function ($u) {
            return $u->stock_entries_count + $u->stock_exits_count;
        })->first();

        return [
            'users' => $users,
            'roles' => Role::withCount('users')->orderBy('name', 'asc')->get(),
            'total_users_count' => $totalUsersCount,
            'active_users_count' => $activeUsersCount,
            'top_user_name' => $topUser ? $topUser->name : '-',
            'top_user_tx' => $topUser ? ($topUser->stock_entries_count + $topUser->stock_exits_count) : 0,
        ];
    }

    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'department' => $data['department'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            $user->assignRole($data['role']);

            return $user;
        });
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'department' => $data['department'],
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);
            $user->syncRoles([$data['role']]);

            return $user;
        });
    }

    public function toggleUserStatus(User $user): bool
    {
        if (auth()->id() === $user->id) {
            throw new \Exception('Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->is_active = !$user->is_active;
        return $user->save();
    }

    public function deleteUser(User $user): bool
    {
        if (auth()->id() === $user->id) {
            throw new \Exception('Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        $user->loadCount(['stockEntries', 'stockExits']);

        if (($user->stock_entries_count + $user->stock_exits_count) > 0) {
            throw new \Exception("Pengguna '{$user->name}' tidak dapat dihapus permanen karena memiliki " . ($user->stock_entries_count + $user->stock_exits_count) . " riwayat transaksi audit. Silakan nonaktifkan status akunnya saja.");
        }

        return $user->delete();
    }

    // ==========================================
    // ROLE MANAGEMENT METHODS (CRUD ROLE)
    // ==========================================

    public function createRole(string $name): Role
    {
        $existing = Role::where('name', $name)->first();
        if ($existing) {
            throw new \Exception("Role '{$name}' sudah ada.");
        }

        return Role::create([
            'name' => $name,
            'guard_name' => 'web'
        ]);
    }

    public function updateRole(Role $role, string $name): Role
    {
        if ($role->name === 'Super Admin') {
            throw new \Exception("Role 'Super Admin' tidak dapat diubah namanya.");
        }

        $existing = Role::where('name', $name)->where('id', '!=', $role->id)->first();
        if ($existing) {
            throw new \Exception("Role '{$name}' sudah digunakan.");
        }

        $role->update(['name' => $name]);
        return $role;
    }

    public function deleteRole(Role $role): bool
    {
        if ($role->name === 'Super Admin') {
            throw new \Exception("Role 'Super Admin' adalah role bawaan sistem dan tidak dapat dihapus.");
        }

        if ($role->users()->count() > 0) {
            throw new \Exception("Role '{$role->name}' tidak dapat dihapus karena masih digunakan oleh " . $role->users()->count() . " pengguna.");
        }

        return $role->delete();
    }
}
