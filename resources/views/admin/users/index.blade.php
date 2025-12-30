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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::count() }}</p>
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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::whereNotNull('email_verified_at')->count() }}</p>
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
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ \App\Models\User::where('is_blocked', true)->count() ?? 0 }}</p>
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

        <div class="flex gap-3">
            <button id="openAddUser" class="px-4 py-2 bg-green-600 text-white rounded">Tambah User</button>
            <a href="{{ route('admin.comics.index') }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded">Kelola Komik</a>
        </div>
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
                        @if($u->is_blocked ?? false)
                            <span class="status-badge bg-red-100 text-red-800">Diblokir</span>
                        @elseif($u->email_verified_at)
                            <span class="status-badge bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="status-badge bg-amber-100 text-amber-800">Menunggu</span>
                        @endif
                    </td>
                    <td class="py-4 px-6"><span class="font-medium">{{ $u->active_borrowings_count ?? 0 }}</span> <span class="text-gray-500 text-sm">komik</span></td>
                    <td class="py-4 px-6">{{ optional($u->created_at)->format('d M Y') }}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button class="edit-user-btn p-2 text-blue-600 hover:bg-blue-50 rounded"
                                    data-id="{{ $u->id }}" data-name="{{ $u->name }}" data-email="{{ $u->email }}" data-status="{{ $u->email_verified_at ? 'active' : 'pending' }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded"><i class="fas fa-trash-alt"></i></button>
                            </form>

                            <a href="{{ route('admin.borrowings.index') }}?user={{ $u->id }}" class="p-2 text-purple-600 hover:bg-purple-50 rounded"><i class="fas fa-eye"></i></a>
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

<!-- Modal Add/Edit User -->
<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Tambah User</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
        </div>

        <form id="userForm" class="p-6" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="userId" name="userId" value="">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input id="userName" name="name" type="text" class="w-full px-4 py-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="userEmail" name="email" type="email" class="w-full px-4 py-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="userPassword" name="password" type="password" class="w-full px-4 py-2 border rounded">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin merubah password</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="userStatus" name="status" class="w-full px-4 py-2 border rounded">
                    <option value="active">Aktif</option>
                    <option value="pending">Menunggu</option>
                    <option value="blocked">Diblokir</option>
                </select>
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
    const openAdd = document.getElementById('openAddUser');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const userIdInput = document.getElementById('userId');
    const userNameInput = document.getElementById('userName');
    const userEmailInput = document.getElementById('userEmail');
    const userPasswordInput = document.getElementById('userPassword');
    const userStatusInput = document.getElementById('userStatus');

    openAdd.addEventListener('click', () => {
        modalTitle.textContent = 'Tambah User Baru';
        userForm.action = "{{ route('admin.users.store') }}";
        userIdInput.value = '';
        userNameInput.value = '';
        userEmailInput.value = '';
        userPasswordInput.value = '';
        userPasswordInput.required = true;
        userStatusInput.value = 'active';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            const email = btn.dataset.email;
            const status = btn.dataset.status;

            modalTitle.textContent = 'Edit User';
            userForm.action = "{{ url('admin/users') }}/" + id;
            // add method field for PUT
            if (!document.getElementById('_method')) {
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.id = '_method';
                method.value = 'PUT';
                userForm.appendChild(method);
            }
            userIdInput.value = id;
            userNameInput.value = name;
            userEmailInput.value = email;
            userPasswordInput.value = '';
            userPasswordInput.required = false;
            userStatusInput.value = status;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });
</script>
@endsection
