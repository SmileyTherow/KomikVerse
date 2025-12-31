@extends('admin.layout')

@section('title', 'Kelola User')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Kelola User</h2>
    <p class="text-gray-600">Kelola data user dan akses mereka ke sistem</p>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">Total User</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::nonAdmins()->count() }}</p>
                <p class="text-sm text-green-600 mt-1">—</p>
            </div>
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-users text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">User Aktif</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::nonAdmins()->where('status','active')->whereNotNull('email_verified_at')->count() }}</p>
                <p class="text-sm text-green-600 mt-1">—</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-user-check text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500 text-sm">User Terblokir</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::nonAdmins()->where('status','blocked')->count() }}</p>
                <p class="text-sm text-red-600 mt-1">—</p>
            </div>
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-user-slash text-red-600 text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input name="q" value="{{ request('q') }}" class="w-full pl-10 pr-3 py-3 border rounded-lg" placeholder="Cari user...">
        </form>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-500 text-sm">
                    <th class="py-4 px-6 font-medium">Nama User</th>
                    <th class="py-4 px-6 font-medium">Email</th>
                    <th class="py-4 px-6 font-medium">Status</th>
                    <th class="py-4 px-6 font-medium">Peminjaman Aktif</th>
                    <th class="py-4 px-6 font-medium">Tanggal Daftar</th>
                    <th class="py-4 px-6 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                <span class="font-medium text-indigo-700">{{ strtoupper(substr($u->name,0,2)) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $u->name }}</p>
                                <p class="text-sm text-gray-500">ID: {{ sprintf('USR%03d', $u->id) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">{{ $u->email }}</td>
                    <td class="py-4 px-6">
                        @if($u->status === 'blocked')
                            <span class="status-badge bg-red-100 text-red-800">Diblokir</span>
                        @elseif($u->status === 'active' && $u->email_verified_at)
                            <span class="status-badge bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="status-badge bg-amber-100 text-amber-800">Menunggu</span>
                        @endif
                    </td>
                    <td class="py-4 px-6"><span class="font-medium">{{ $u->active_borrowings_count ?? 0 }}</span> <span class="text-gray-500 text-sm">komik</span></td>
                    <td class="py-4 px-6">{{ optional($u->created_at)->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <!-- Open modal to change status only -->
                            <button class="change-status-btn p-2 text-blue-600 hover:bg-blue-50 rounded"
                                    data-id="{{ $u->id }}" data-status="{{ $u->status ?? 'pending' }}" title="Ubah status">
                                <i class="fas fa-user-cog"></i>
                            </button>

                            <!-- No delete button: admin tidak boleh menghapus user -->
                            <!-- Link to user's borrowings -->
                            <a href="{{ route('admin.borrowings.index') }}?user={{ $u->id }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded" title="Lihat peminjaman"><i class="fas fa-eye"></i></a>

                            <!-- Send message -->
                            <a href="{{ route('admin.users.showMessageForm', $u->id) }}" class="p-2 text-green-600 hover:bg-green-50 rounded" title="Kirim pesan"><i class="fas fa-envelope"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t flex justify-between items-center">
        <p class="text-sm text-gray-600">Menampilkan {{ $users->count() }} dari {{ $users->total() }} user</p>
        <div>{{ $users->withQueryString()->links() }}</div>
    </div>
</div>

<!-- Modal Change Status -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Ubah Status User</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>

        <form id="userForm" class="p-6" method="POST">
            @csrf
            <input type="hidden" id="userId" name="userId" value="">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="userStatus" name="status" class="w-full px-4 py-2 border rounded">
                    <option value="active">Aktif</option>
                    <option value="pending">Menunggu</option>
                    <option value="blocked">Diblokir</option>
                </select>
            </div>

            <div class="mb-4 flex items-center gap-2">
                <input type="checkbox" id="notify" name="notify" value="1" class="h-4 w-4">
                <label for="notify" class="text-sm text-gray-600">Kirim notifikasi email ke user</label>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" id="cancelBtn" class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" id="saveBtn" class="px-4 py-2 bg-indigo-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('userModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const userIdInput = document.getElementById('userId');
    const userStatusInput = document.getElementById('userStatus');
    const notifyInput = document.getElementById('notify');

    // base URL for status update (POST /admin/users/{id}/status)
    const baseStatusUrl = "{{ url('admin/users') }}";

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        // reset notify checkbox
        notifyInput.checked = false;
    }

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    document.querySelectorAll('.change-status-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const status = btn.dataset.status || 'pending';

            modalTitle.textContent = 'Ubah Status User';
            userForm.action = baseStatusUrl + '/' + id + '/status';
            userIdInput.value = id;
            userStatusInput.value = status;
            notifyInput.checked = false;

            openModal();
        });
    });

    // Optional: client-side form submit handling left default (POST)
    // If you want to submit via AJAX, implement fetch/XHR here.
</script>
@endsection
