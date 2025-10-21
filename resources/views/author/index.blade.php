@extends('layouts.master')

@section('title', 'Manage Authors – BeatHive')

@section('content')
<div class="page-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Authors</h5>
            <a href="{{ route('author.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add Author
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name / Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($authors ?? collect()) as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $a->name }}</div>
                                <div class="text-muted small">{{ '@'.$a->username }}</div>
                            </td>
                            <td>{{ $a->email }}</td>
                            <td>
                                @if($a->role === 1)
                                <span class="badge bg-danger">Admin</span>
                                @elseif($a->role === 2)
                                <span class="badge bg-info text-dark">Author</span>
                                @else
                                <span class="badge bg-secondary">User</span>
                                @endif
                            </td>
                            <td>
                                @if($a->status)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-light text-muted">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('author.edit', $a->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('author.show', $a->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('author.destroy', $a->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this author?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No authors found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $authors->links() }}
        </div>
    </div>
</div>
@endsection