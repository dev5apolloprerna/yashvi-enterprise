@extends('layouts.front')

@section('title', $blog->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($blog->image)
                <img src="{{ asset('storage/'.$blog->image) }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}">
            @endif

            <h1>{{ $blog->title }}</h1>
            <p class="text-muted">{{ $blog->created_at->format('d M, Y') }} &bull; {{ $blog->views }} views</p>
            <div class="content">{!! $blog->description !!}</div>

            @if($related->count())
                <hr class="my-5">
                <h4>Related Blogs</h4>
                <div class="row g-3">
                    @foreach($related as $r)
                        <div class="col-md-4">
                            <a href="{{ route('front.show', $r->slug) }}" class="text-decoration-none">
                                <div class="card blog-card">
                                    @if($r->image)
                                        <img src="{{ asset('storage/'.$r->image) }}" class="card-img-top">
                                    @endif
                                    <div class="card-body"><h6 class="mb-0 text-dark">{{ $r->title }}</h6></div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('front.home') }}" class="btn btn-outline-secondary mt-4">&larr; Back to Blog</a>
        </div>
    </div>
</div>
@endsection
