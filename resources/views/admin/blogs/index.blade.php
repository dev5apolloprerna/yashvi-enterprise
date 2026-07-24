@extends('layouts.admin')

@section('title', 'Manage Blogs')

@section('content')
<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search blogs..." value="{{ request('search') }}">
            <button class="btn btn-outline-secondary"><i class="fa-solid fa-search"></i></button>
        </form>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Blog
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                    <tr>
                        <td>
                            @if($blog->image)
                                <img src="{{ asset('storage/'.$blog->image) }}" width="60" height="45" style="object-fit:cover;border-radius:6px;">
                            @else
                                <div class="bg-secondary bg-opacity-25 rounded" style="width:60px;height:45px;"></div>
                            @endif
                        </td>
                        <td>{{ $blog->title }}</td>
                        <td>{{ $blog->user->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </td>
                        <td>{{ $blog->views }}</td>
                        <td>{{ $blog->created_at->format('d M, Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this blog? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No blogs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">{{ $blogs->links() }}</div>
</div>
@endsection
