@extends('layouts.app')

@section('content')
    <h1>Permintaan Peminjaman (Admin)</h1>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Komik</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->user->name }} ({{ $r->user->email }})</td>
                    <td>{{ $r->comic->title }}</td>
                    <td>{{ $r->requested_at }}</td>
                    <td>
                        <form action="{{ route('admin.borrowings.approve', $r->id) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-sm btn-primary">Approve</button>
                        </form>
                        {{-- return not relevant for requested items --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada request.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
