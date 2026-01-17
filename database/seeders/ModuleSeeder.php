<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = \App\Models\Institute::all();
        $modules1 = [
            ['module_code' => 'ITP4514', 'translations' => [
                ['locale' => 'en', 'name' => 'Artificial Intelligence and Machine Learning'],
                ['locale' => 'zh-HK', 'name' => '人工智慧和機器學習'],
                ['locale' => 'zh-CN', 'name' => '人工智能和机器学习'],
            ]],
            ['module_code' => 'ITP4507', 'translations' => [
                ['locale' => 'en', 'name' => 'Contemporary Topics in Software Engineering'],
                ['locale' => 'zh-HK', 'name' => '當代軟件工程話題'],
                ['locale' => 'zh-CN', 'name' => '当代软件工程话题'],
            ]],
            ['module_code' => 'ITP4506', 'translations' => [
                ['locale' => 'en', 'name' => 'Human Computer Interaction and GUI Programming'],
                ['locale' => 'zh-HK', 'name' => '人機互動和圖形使用者介面編程'],
                ['locale' => 'zh-CN', 'name' => '人机互动和图形用户界面编程'],
            ]],
            ['module_code' => 'ITE4103', 'translations' => [
                ['locale' => 'en', 'name' => 'IT Professionalism'],
                ['locale' => 'zh-HK', 'name' => '資訊科技專業精神'],
                ['locale' => 'zh-CN', 'name' => '信息技术专业精神'],
            ]],
            ['module_code' => 'LAN4102', 'translations' => [
                ['locale' => 'en', 'name' => 'Professional Workplace Communication Storytelling and Job Search'],
                ['locale' => 'zh-HK', 'name' => '專業職場溝通、故事敘述與求職'],
                ['locale' => 'zh-CN', 'name' => '专业职场沟通、故事叙述与求职'],
            ]],
            ['module_code' => 'LAN3003', 'translations' => [
                ['locale' => 'en', 'name' => 'Vocational Chinese Communication Putonghua Conversation and Reports'],
                ['locale' => 'zh-HK', 'name' => '普通話會話與報告'],
                ['locale' => 'zh-CN', 'name' => '普通话会话与报告'],
            ]],
        ];

        $modules2 = [
            ['module_code' => 'ITE4103', 'translations' => [
                ['locale' => 'en', 'name' => 'IT Professionalism'],
                ['locale' => 'zh-HK', 'name' => '資訊科技專業精神'],
                ['locale' => 'zh-CN', 'name' => '信息技术专业精神'],
            ]],
            ['module_code' => 'LAN4102', 'translations' => [
                ['locale' => 'en', 'name' => 'Professional Workplace Communication Storytelling and Job Search'],
                ['locale' => 'zh-HK', 'name' => '專業職場溝通、故事敘述與求職'],
                ['locale' => 'zh-CN', 'name' => '专业职场沟通、故事叙述与求职'],
            ]],
            ['module_code' => 'LAN3003', 'translations' => [
                ['locale' => 'en', 'name' => 'Vocational Chinese Communication Putonghua Conversation and Reports'],
                ['locale' => 'zh-HK', 'name' => '普通話會話與報告'],
                ['locale' => 'zh-CN', 'name' => '普通话会话与报告'],
            ]],
        ];

        foreach ($modules1 as $data) {
            $module = \App\Models\Module::create([
                'institute_id' => $institute[0]->id,
                'module_code' => $data['module_code'],
            ]);
            $module->moduleTranslation()->createMany($data['translations']);
        }

        foreach ($modules2 as $data) {
            $module = \App\Models\Module::create([
                'institute_id' => $institute[1]->id,
                'module_code' => $data['module_code'],
            ]);
            $module->moduleTranslation()->createMany($data['translations']);
        }
    }
}
