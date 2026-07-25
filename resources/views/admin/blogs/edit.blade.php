@extends('layouts.admin')

@section('title', 'Edit Blog')

@section('content')
<div class="card p-4">
    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $blog->short_description) }}</textarea>
        </div>

        @if($blog->image)
            <div class="mb-3">
                <label class="form-label d-block">Current Image</label>
                <img src="{{ asset($blog->image) }}" width="150" class="rounded mb-2">
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Featured Image (leave blank to keep current)</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="description" id="editor" class="form-control @error('description') is-invalid @enderror" rows="10">{{ old('description', $blog->description) }}</textarea>
            @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="published" {{ $blog->status === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ $blog->status === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <button class="btn btn-primary"><i class="fa-solid fa-check"></i> Update Blog</button>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editor');</script>
@endsection
