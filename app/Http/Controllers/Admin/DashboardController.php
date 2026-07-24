<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBlogs   = Blog::count();
        $published    = Blog::where('status', 'published')->count();
        $draft        = Blog::where('status', 'draft')->count();
        $recentBlogs  = Blog::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBlogs', 'published', 'draft', 'recentBlogs'));
    }
}
