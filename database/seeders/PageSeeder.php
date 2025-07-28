<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => 'Write about your restaurant here...',
            'status' => true
        ]);

        Page::create([
            'title' => 'Terms & Conditions',
            'slug' => 'terms',
            'content' => 'List your terms here...',
            'status' => true
        ]);

        Page::create([
            'title' => 'Return Policy',
            'slug' => 'return-policy',
            'content' => 'Describe your return/refund policy...',
            'status' => true
        ]);
    }
}
