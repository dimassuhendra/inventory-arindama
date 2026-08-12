<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Users;
use Spatie\Permission\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data = $this->userService->getUserPageData($request);
        $data['pageTitle'] = 'Manajemen Pengguna & Akses';

        return view('users', $data);
    }

    public function store(UserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(UserRequest $request, Users $user)
    {
        try {
            $this->userService->updateUser($user, $request->validated());
            return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Users $user)
    {
        try {
            $this->userService->toggleUserStatus($user);
            $statusText = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->back()->with('success', "Status pengguna {$user->name} berhasil {$statusText}.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Users $user)
    {
        try {
            $this->userService->deleteUser($user);
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $this->userService->createRole($request->name);
            return redirect()->back()->with('success', 'Role baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate(['name' => 'required|string|max:255']);

        try {
            $this->userService->updateRole($role, $request->name);
            return redirect()->back()->with('success', 'Nama role berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroyRole(Role $role)
    {
        try {
            $this->userService->deleteRole($role);
            return redirect()->back()->with('success', 'Role berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
