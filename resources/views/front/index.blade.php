@extends('layouts.front')

@section('title', 'Blog')

@section('content')
<div class="hero text-center">
    <h1>Yashvi Enterprise Blog</h1>
    <p>Insights, updates &amp; stories from our team</p>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($blogs as $blog)
            <div class="col-md-4">
                <div class="card blog-card h-100">
                    @if($blog->image)
                        <img src="{{ asset('storage/'.$blog->image) }}" class="card-img-top" alt="{{ $blog->title }}">
                    @else
                        <div class="bg-secondary bg-opacity-25" style="height:200px;"></div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($blog->short_description, 100) }}</p>
                        <a href="{{ route('front.show', $blog->slug) }}" class="btn btn-sm btn-outline-primary">Read More</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">No blogs published yet.</p>
        @endforelse
    </div>

    <div class="mt-4">{{ $blogs->links() }}</div>
</div>
@endsection
