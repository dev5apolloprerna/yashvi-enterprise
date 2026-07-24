<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * List all blogs (admin) with search + pagination.
     */
    public function index(Request $request)
    {
        $query = Blog::with('user')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $blogs = $query->paginate(10)->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the create blog form.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'short_description'  => ['nullable', 'string', 'max:500'],
            'description'        => ['required', 'string'],
            'status'             => ['required', 'in:published,draft'],
            'image'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $slug = $this->generateUniqueSlug($validated['title']);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        Blog::create([
            'user_id'            => Auth::id(),
            'title'              => $validated['title'],
            'slug'               => $slug,
            'image'              => $imagePath,
            'short_description'  => $validated['short_description'] ?? null,
            'description'        => $validated['description'],
            'status'             => $validated['status'],
        ]);

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully.');
    }

    /**
     * Show the edit blog form.
     */
    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Update an existing blog.
     */
    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'short_description'  => ['nullable', 'string', 'max:500'],
            'description'        => ['required', 'string'],
            'status'             => ['required', 'in:published,draft'],
            'image'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($blog->title !== $validated['title']) {
            $blog->slug = $this->generateUniqueSlug($validated['title'], $blog->id);
        }

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('public')->delete($blog->image);
            }
            $blog->image = $request->file('image')->store('blogs', 'public');
        }

        $blog->title             = $validated['title'];
        $blog->short_description = $validated['short_description'] ?? null;
        $blog->description       = $validated['description'];
        $blog->status             = $validated['status'];
        $blog->save();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully.');
    }

    /**
     * Delete a blog.
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog deleted successfully.');
    }

    /**
     * Generate a unique slug for a blog title.
     */
    protected function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (
            Blog::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
