<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Borrowing;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $query = User::query()->orderByDesc('created_at');

        if ($q) {
            $query->where(function($qf) use ($q) {
                $qf->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(12);

        // eager load active borrow counts
        $users->getCollection()->transform(function ($user) {
            $user->active_borrowings_count = Borrowing::where('user_id', $user->id)
                ->where('status', \App\Models\Borrowing::STATUS_DIPINJAM)
                ->count();
            return $user;
        });

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'status' => ['nullable', Rule::in(['active','pending','blocked'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_admin' => false,
            'email_verified_at' => $data['status'] === 'active' ? Carbon::now() : null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'status' => ['nullable', Rule::in(['active','pending','blocked'])],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['status'])) {
            if ($data['status'] === 'active') {
                $user->email_verified_at = $user->email_verified_at ?? Carbon::now();
            } elseif ($data['status'] === 'pending') {
                $user->email_verified_at = null;
            } elseif ($data['status'] === 'blocked') {
                // leave email_verified_at as is, but we could mark blocked with a flag
            }
            // For simplicity, store status in a user meta boolean 'is_blocked' if you want.
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }
}
