<?php

namespace Database\Seeders;

use App\Helpers\LocalesHelper;
use App\Models\Resource;
use App\Models\ResourceTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Have file added
        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Student Handbook 2026/27',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '學生手冊 2026/27',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '学生手册 2026/27',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/student-handbook-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/student-handbook-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-handbook-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/student-handbook-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-handbook-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/student-handbook-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Academic Calendar 2026/27',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '2026/27學年行事曆',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '2026/27学年行事历',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/academic-calendar-2026-2027-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/academic-calendar-2026-2027-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/academic-calendar-2026-2027-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/academic-calendar-2026-2027-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/academic-calendar-2026-2027-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/academic-calendar-2026-2027-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        // No file added
        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Programme Guide – Higher Diploma',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '高級文憑課程指南',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '高级文凭课程指南',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/programme-guide-higher-diploma-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/programme-guide-higher-diploma-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/programme-guide-higher-diploma-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/programme-guide-higher-diploma-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/programme-guide-higher-diploma-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/programme-guide-higher-diploma-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Assessment and Examination Regulations',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '評核及考試規則',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '评核及考试规定',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/assessment-and-examination-regulations-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/assessment-and-examination-regulations-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/assessment-and-examination-regulations-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/assessment-and-examination-regulations-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/assessment-and-examination-regulations-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/assessment-and-examination-regulations-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Student Conduct and Discipline Guidelines',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '學生操行及紀律指引',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '学生操行及纪律指引',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/student-conduct-and-discipline-guidelines-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Financial Assistance and Scholarships Guide',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '助學金及獎學金指南',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '助学金及奖学金指南',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/financial-assistance-and-scholarships-guide-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'IT Services and Email User Guide',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '資訊科技服務及電郵使用指南',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '资讯科技服务及电邮使用指南',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/it-services-and-email-user-guide-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/it-services-and-email-user-guide-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/it-services-and-email-user-guide-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/it-services-and-email-user-guide-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/it-services-and-email-user-guide-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/it-services-and-email-user-guide-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Library Services and Regulations',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '圖書館服務及規則',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '图书馆服务及规则',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/library-services-and-regulations-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/library-services-and-regulations-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/library-services-and-regulations-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/library-services-and-regulations-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/library-services-and-regulations-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/library-services-and-regulations-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Student Counselling and Support Services',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '學生輔導及支援服務',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '学生辅导及支援服务',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/student-counselling-and-support-services-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/student-counselling-and-support-services-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-counselling-and-support-services-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/student-counselling-and-support-services-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/student-counselling-and-support-services-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/student-counselling-and-support-services-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Work-Integrated Learning and Internship Handbook',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '職學兼備及實習手冊',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '职学兼备及实习手册',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/work-integrated-learning-and-internship-handbook-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        $resource = Resource::create();

        $resource->resourceTranslation()->createMany([
            [
                'locale' => 'en',
                'title'  => 'Campus Facilities and Safety Information',
            ],
            [
                'locale' => 'zh-HK',
                'title'  => '校園設施及安全資訊',
            ],
            [
                'locale' => 'zh-CN',
                'title'  => '校园设施及安全资讯',
            ],
        ]);

        $translations = $resource->resourceTranslation;

        if (file_exists(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-en.pdf'))) {
            $translations->firstWhere('locale', 'en')
                ->addMedia(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-en.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-zh-HK.pdf'))) {
            $translations->firstWhere('locale', 'zh-HK')
                ->addMedia(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-zh-HK.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }

        if (file_exists(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-zh-CN.pdf'))) {
            $translations->firstWhere('locale', 'zh-CN')
                ->addMedia(database_path('seeders/seeders_resources/campus-facilities-and-safety-information-zh-CN.pdf'))
                ->preservingOriginal()
                ->toMediaCollection('resources');
        }
        /*
        Resource::factory()
            ->count(2)
            ->has(ResourceTranslation::factory()
                ->count(3)
                ->resource()
                ->sequence(...Arr::map(LocalesHelper::locales(), fn($item) => ['locale' => $item])))
            ->create();
        */
    }
}
