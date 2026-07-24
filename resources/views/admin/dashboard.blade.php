@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#4b2e83,#6f42c1);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">Total Blogs</h6>
                    <h2 class="mb-0">{{ $totalBlogs }}</h2>
                </div>
                <i class="fa-solid fa-newspaper fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#1fa571,#38d996);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">Published</h6>
                    <h2 class="mb-0">{{ $published }}</h2>
                </div>
                <i class="fa-solid fa-circle-check fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#d68c00,#ffb648);">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-1">Draft</h6>
                    <h2 class="mb-0">{{ $draft }}</h2>
                </div>
                <i class="fa-solid fa-file-pen fa-2x opacity-75"></i>
            </div>
        </div>
    </div>
</div>

<div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Recent Blogs</h5>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Add Blog
        </a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBlogs as $blog)
                    <tr>
                        <td>{{ $blog->title }}</td>
                        <td>
                            <span class="badge bg-{{ $blog->status === 'published' ? 'success' : 'warning' }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </td>
                        <td>{{ $blog->views }}</td>
                        <td>{{ $blog->created_at->format('d M, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">No blogs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
