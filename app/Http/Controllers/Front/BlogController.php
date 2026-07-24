<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * Public blog listing page.
     */
    public function index()
    {
        $blogs = Blog::published()->latest()->paginate(6);

        return view('front.index', compact('blogs'));
    }

    /**
     * Public single blog page.
     */
    public function show($slug)
    {
        $blog = Blog::published()->where('slug', $slug)->firstOrFail();
        $blog->increment('views');

        $related = Blog::published()
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('front.show', compact('blog', 'related'));
    }
}
