<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminMessageToUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserManagementController extends Controller
{
    public function __construct()
    {
        // Pastikan middleware auth + is_admin terdaftar
        $this->middleware(['auth', 'is_admin']);
    }

    // Daftar user (exclude admin)
    public function index(Request $request)
    {
        $q = $request->query('q');
        $users = User::nonAdmins()
            ->when($q, fn($query) => $query->where(function($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // Update hanya status user (active/blocked/pending)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,blocked,pending',
        ]);

        $user = User::nonAdmins()->findOrFail($id);

        $user->status = $request->input('status');
        $user->save();

        // (opsional) kirim pemberitahuan email ke user bahwa statusnya berubah
        if ($request->boolean('notify', false)) {
            Mail::to($user->email)->send(new AdminMessageToUser(
                subject: 'Perubahan Status Akun',
                message: "Halo {$user->name},\n\nStatus akun Anda telah diubah menjadi: {$user->status}.\n\nJika ada pertanyaan, hubungi admin.",
                adminName: $request->user()->name,
            ));
        }

        return back()->with('success', 'Status user diperbarui.');
    }

    // Tampilkan form kirim pesan ke user
    public function showMessageForm($id)
    {
        $user = User::nonAdmins()->findOrFail($id);
        return view('admin.users.send_message', compact('user'));
    }

    // Proses kirim pesan dari admin ke user
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = User::nonAdmins()->findOrFail($id);

        Mail::to($user->email)->send(new AdminMessageToUser(
            subject: $request->input('subject'),
            message: $request->input('message'),
            adminName: $request->user()->name,
        ));

        return redirect()->route('admin.users.index')->with('success', 'Pesan terkirim ke user.');
    }
}
