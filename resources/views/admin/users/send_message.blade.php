@extends('admin.layout')

@section('title', 'Kirim Pesan')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">Kirim Pesan ke {{ $user->name }}</h2>
            <p class="text-sm text-gray-500 mt-1">Gunakan form berikut untuk mengirim pemberitahuan atau pengingat kepada pengguna.</p>
        </div>

        <form action="{{ route('admin.users.sendMessage', $user->id) }}" method="POST" class="px-6 py-6">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200" required maxlength="255">
                @error('subject')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                <textarea name="message" rows="6" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-200" required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Kirim</button>
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
