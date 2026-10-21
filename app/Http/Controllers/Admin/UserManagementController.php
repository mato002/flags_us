<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with('roles')->orderBy('name');

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(fn ($q) => $q->where('name', 'like', $like)->orWhere('email', 'like', $like));
        }

        return view('admin.system.users.index', [
            'users' => $query->paginate(25)->withQueryString(),
        ]);
    }

    public function edit(User $user): View
    {
        return view('admin.system.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($validated['roles'] ?? []);

        return redirect()->route('admin.system.users.index')->with('status', 'User roles updated.');
    }
}
