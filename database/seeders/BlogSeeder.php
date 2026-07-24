<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@yashvienterprise.com')->first();

        if (! $admin) {
            return;
        }

        $samples = [
            [
                'title' => 'Welcome to Yashvi Enterprise Blog',
                'short_description' => 'A quick introduction to what you can expect from our blog.',
                'description' => '<p>Welcome to the official blog of <strong>Yashvi Enterprise</strong>. Here we will share company updates, industry insights, and helpful articles for our customers.</p>',
            ],
            [
                'title' => 'Why Quality Matters in Everything We Do',
                'short_description' => 'A look at our commitment to quality and customer satisfaction.',
                'description' => '<p>At Yashvi Enterprise, quality is not an afterthought — it is built into every step of our process. In this article we explore how our team ensures the best outcomes for every client.</p>',
            ],
            [
                'title' => '5 Tips for Growing Your Business in 2026',
                'short_description' => 'Practical, actionable tips to help your business grow this year.',
                'description' => '<p>Growing a business takes strategy and consistency. Here are five practical tips that have helped our clients scale successfully this year.</p>',
            ],
        ];

        foreach ($samples as $sample) {
            Blog::updateOrCreate(
                ['slug' => Str::slug($sample['title'])],
                [
                    'user_id' => $admin->id,
                    'title' => $sample['title'],
                    'short_description' => $sample['short_description'],
                    'description' => $sample['description'],
                    'status' => 'published',
                ]
            );
        }
    }
}
