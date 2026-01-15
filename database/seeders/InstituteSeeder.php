<?php

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\InstituteTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hkdi = Institute::create();
        $hkdi->instituteTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'name' => 'Hong Kong Design Institute',
                ],
                [
                    'locale' => 'zh-HK',
                    'name' => '香港知專設計學院',
                ],
                [
                    'locale' => 'zh-CN',
                    'name' => '香港知专设计学院',
                ],
            ]);

        $ive = Institute::create();
        $ive->instituteTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'name' => 'Hong Kong Institute of Vocational Education',
                ],
                [
                    'locale' => 'zh-HK',
                    'name' => '香港專業教育學院',
                ],
                [
                    'locale' => 'zh-CN',
                    'name' => '香港专业教育学院',
                ],
            ]);

        $hkiit = Institute::create();
        $hkiit->instituteTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'name' => 'Hong Kong Institute of Information Technology',
                ],
                [
                    'locale' => 'zh-HK',
                    'name' => '香港資訊科技學院',
                ],
                [
                    'locale' => 'zh-CN',
                    'name' => '香港资讯科技学院',
                ],
            ]);
    }
}
