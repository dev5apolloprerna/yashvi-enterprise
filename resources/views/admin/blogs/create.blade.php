@extends('layouts.admin')

@section('title', 'Add Blog')

@section('content')
<div class="card p-4">
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Short Description</label>
            <textarea name="short_description" class="form-control" rows="2">{{ old('short_description') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Featured Image</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="description" id="editor" class="form-control @error('description') is-invalid @enderror" rows="10">{{ old('description') }}</textarea>
            @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <button class="btn btn-primary"><i class="fa-solid fa-check"></i> Save Blog</button>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>CKEDITOR.replace('editor');</script>
@endsection
