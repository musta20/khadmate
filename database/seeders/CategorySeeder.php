<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'تطوير الويب',
            'تطوير تطبيقات الجوال',
            'التصميم الجرافيكي',
            'التسويق الرقمي',
            'الكتابة والترجمة',
            'الفيديو والرسوم المتحركة',
            'الموسيقى والصوت',
            'البرمجة والتكنولوجيا',
            'إدخال البيانات',
            'الأعمال',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

    }
}
