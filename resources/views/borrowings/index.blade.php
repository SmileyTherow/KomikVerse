@extends('layouts.app')

@section('content')
    <h1>Pinjamanku</h1>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Komik</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Borrowed At</th>
                <th>Due At</th>
                <th>Returned At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $b)
                <tr>
                    <td>{{ $b->id }}</td>
                    <td>{{ $b->comic?->title }}</td>
                    <td>{{ $b->status }}</td>
                    <td>{{ $b->requested_at }}</td>
                    <td>{{ $b->borrowed_at }}</td>
                    <td>{{ $b->due_at }}</td>
                    <td>{{ $b->returned_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
