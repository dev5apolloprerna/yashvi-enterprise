<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Absolute server directory where blog images will be stored.
     */
    private const BLOG_IMAGE_DIRECTORY =
        '/home1/getdemo/public_html/yashvi_enterprise/images/blogs';

    /**
     * Relative image directory stored in the database.
     */
    private const BLOG_IMAGE_DB_PATH = 'images/blogs';

    /**
     * List all blogs with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Blog::with('user')->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere(
                        'short_description',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $blogs = $query->paginate(10)->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show create blog form.
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
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'description' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                'in:published,draft',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $slug = $this->generateUniqueSlug($validated['title']);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->uploadBlogImage(
                $request->file('image'),
                $slug
            );
        }

        Blog::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'slug' => $slug,
            'image' => $imagePath,
            'short_description' =>
                $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Show edit blog form.
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
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'short_description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'description' => [
                'required',
                'string',
            ],
            'status' => [
                'required',
                'in:published,draft',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'remove_image' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Update slug if title has changed.
         */
        if ($blog->title !== $validated['title']) {
            $blog->slug = $this->generateUniqueSlug(
                $validated['title'],
                $blog->id
            );
        }

        /*
         * Delete current image when remove image is selected.
         */
        if ($request->boolean('remove_image')) {
            $this->deleteBlogImage($blog->image);
            $blog->image = null;
        }

        /*
         * Replace current image with a newly uploaded image.
         */
        if ($request->hasFile('image')) {
            $this->deleteBlogImage($blog->image);

            $blog->image = $this->uploadBlogImage(
                $request->file('image'),
                $blog->slug
            );
        }

        $blog->title = $validated['title'];
        $blog->short_description =
            $validated['short_description'] ?? null;
        $blog->description = $validated['description'];
        $blog->status = $validated['status'];

        $blog->save();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    /**
     * Delete blog record and its related image.
     */
    public function destroy(Blog $blog)
    {
        /*
         * Delete the physical image first.
         */
        $this->deleteBlogImage($blog->image);

        /*
         * Delete the database record.
         */
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with(
                'success',
                'Blog and its image deleted successfully.'
            );
    }

    /**
     * Upload image to:
     *
     * /home1/getdemo/public_html/yashvi_enterprise/images/blogs
     */
    private function uploadBlogImage(
        $image,
        string $slug
    ): string {
        $uploadDirectory = self::BLOG_IMAGE_DIRECTORY;

        /*
         * Create the folder if it does not exist.
         */
        if (!File::isDirectory($uploadDirectory)) {
            File::makeDirectory(
                $uploadDirectory,
                0755,
                true,
                true
            );
        }

        $extension = strtolower(
            $image->getClientOriginalExtension()
        );

        $fileName = $slug
            . '-'
            . time()
            . '-'
            . Str::lower(Str::random(8))
            . '.'
            . $extension;

        /*
         * Move file to the exact public_html directory.
         */
        $image->move($uploadDirectory, $fileName);

        /*
         * Store relative path in the database.
         */
        return self::BLOG_IMAGE_DB_PATH . '/' . $fileName;
    }

    /**
     * Delete blog image from the exact public_html directory.
     */
    private function deleteBlogImage(
        ?string $imagePath
    ): void {
        if (empty($imagePath)) {
            return;
        }

        /*
         * Do not attempt to delete external URLs.
         */
        if (
            Str::startsWith($imagePath, [
                'http://',
                'https://',
            ])
        ) {
            return;
        }

        /*
         * Get only the image filename.
         *
         * Example:
         * images/blogs/test.jpg becomes test.jpg
         */
        $fileName = basename($imagePath);

        $absoluteImagePath =
            self::BLOG_IMAGE_DIRECTORY . '/' . $fileName;

        if (File::exists($absoluteImagePath)) {
            File::delete($absoluteImagePath);
        }
    }

    /**
     * Generate unique blog slug.
     */
    protected function generateUniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($title);

        if (empty($slug)) {
            $slug = 'blog';
        }

        $originalSlug = $slug;
        $count = 1;

        while (
            Blog::where('slug', $slug)
                ->when(
                    $ignoreId,
                    function ($query) use ($ignoreId) {
                        $query->where(
                            'id',
                            '!=',
                            $ignoreId
                        );
                    }
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}