<?php

namespace Database\Seeders;

use App\Helpers\LocalesHelper;
use App\Models\CarouselSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CarouselSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            [
                'position' => 1,
                'image' => 'VTC_slide1.jpg',
                'title_key' => 'home.slides.apprenticeship.title',
                'description_key' => 'home.slides.apprenticeship.description',
                'url' => '/news/vtc-apprenticeship-learn-and-earn-pathway',
            ],
            [
                'position' => 2,
                'image' => 'VTC_slide2.jpg',
                'title_key' => 'home.slides.sen_support.title',
                'description_key' => 'home.slides.sen_support.description',
                'url' => '/news/support-services-for-students-with-sen',
            ],
            [
                'position' => 3,
                'image' => 'VTC_slide3.jpg',
                'title_key' => 'home.slides.programme_info_day.title',
                'description_key' => 'home.slides.programme_info_day.description',
                'url' => '/news/programme-selection-info-day',
            ],
            [
                'position' => 4,
                'image' => 'VTC_slide4.jpg',
                'title_key' => 'home.slides.dse_workshop.title',
                'description_key' => 'home.slides.dse_workshop.description',
                'url' => '/news/dse-results-release-talk-and-workshop',
            ],
            [
                'position' => 5,
                'image' => 'VTC_slide5.jpg',
                'title_key' => 'home.slides.curriculum_day.title',
                'description_key' => 'home.slides.curriculum_day.description',
                'url' => '/news/curriculum-information-and-experience-day-infoday',
            ],
        ];

        foreach ($defaults as $item)
        {
            $slide = CarouselSlide::query()->firstOrCreate(
                ['position' => $item['position']],
                [
                    'is_active' => true,
                    'link_url' => $item['url'],
                ]
            );

            foreach (LocalesHelper::locales() as $locale)
            {
                $slide->carouselSlideTranslation()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => trans($item['title_key'], [], $locale),
                        'description' => trans($item['description_key'], [], $locale),
                    ]
                );
            }

            $imagePath = Storage::disk('local')->path('seeders/carousel/' . $item['image']);

            if (file_exists($imagePath))
            {
                $slide->addMedia($imagePath)->preservingOriginal()->toMediaCollection('image');
            }
        }
    }
}
