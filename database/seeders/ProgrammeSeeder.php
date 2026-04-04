<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = \App\Models\Institute::all();
        $programmes = [
            ['programme_code' => 'DE114112', 'translations' => [
                ['locale' => 'en', 'name' => 'Higher Diploma in Illustration Design'],
                ['locale' => 'zh-HK', 'name' => '高級文憑（插畫設計）'],
                ['locale' => 'zh-CN', 'name' => '高级文凭（插画设计）'],

            ]],
        ];
        $programmes2 = [
            ['programme_code' => 'IT114105', 'translations' => [
                ['locale' => 'en', 'name' => 'Higher Diploma in Software Engineering'],
                ['locale' => 'zh-HK', 'name' => '高級文憑（軟件工程）'],
                ['locale' => 'zh-CN', 'name' => '高级文凭（软件工程）'],
            ]],
            ['programme_code' => 'IT114103', 'translations' => [
                ['locale' => 'en', 'name' => 'Higher Diploma in Telecommunications and Networking'],
                ['locale' => 'zh-HK', 'name' => '高級文憑（電訊及網絡）'],
                ['locale' => 'zh-CN', 'name' => '高级文凭（电讯及网络）'],
            ]],
        ];
        foreach ($programmes as $data) {
            $programme = \App\Models\Programme::firstOrCreate([
                'institute_id' => $institute[1]->id,
                'programme_code' => $data['programme_code'],
            ]);
            $programme->programmeTranslation()->createMany($data['translations']);
        }
        foreach ($programmes2 as $data) {
            $programme = \App\Models\Programme::firstOrCreate([
                'institute_id' => $institute[2]->id,
                'programme_code' => $data['programme_code'],
            ]);
            $programme->programmeTranslation()->createMany($data['translations']);
        }
    }
}
