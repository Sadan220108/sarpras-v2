<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

// Khusus admin: kelola akun dan peran
class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();

        return view('admin.users.index', ['users' => $users, 'roles' => User::ROLES]);
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User(), 'roles' => User::ROLES]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'jabatan'  => 'nullable|string|max:255',
            'unit'     => 'nullable|string|max:255',
            'password' => 'required|string|min:8',
        ]);

        User::create($data); // password di-hash otomatis oleh cast pada model

        return redirect()->route('admin.users.index')->with('sukses', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', ['user' => $user, 'roles' => User::ROLES]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
            'jabatan'  => 'nullable|string|max:255',
            'unit'     => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        // Admin tidak boleh menurunkan perannya sendiri
        if ($user->id === auth()->id()) {
            $data['role'] = $user->role;
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('sukses', 'Akun diperbarui.');
    }
}
