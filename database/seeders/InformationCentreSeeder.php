<?php

namespace Database\Seeders;

use App\Enums\InformationCentreStatus;
use App\Models\InformationCentre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InformationCentreSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Student Handbook 2025-26 (Chinese) DVE-PT (YCPF)',
            'Youth College (International) Student Handbook (AY2025/26)',
            'AY2025/26 Youth College (Kwai Fong) Student Handbook (Full-Time Programmes)',
            'AY2025/26 Youth College (Kwai Fong) Student Handbook (Part-Time Programmes)',
            'Moodle Basic Guide for Students',
            'Moodle Quick Guide for Students',
            '2025/26 Student Handbook - MSTI',
            'THEi MBA Programme Document - Cohort 2025',
            'Department of Hospitality and Business Management - Programme Documents - Cohort 2025',
            'Department of Sport and Recreation - Programme Documents - Cohort 2025',
            'AY2025/26 DVE YC(KB) Full-Time Student Handbook',
            'Department of Design and Architecture - Programme Documents - Cohort 2025'
        ];

        $documentCount = 0;
        
        foreach ($titles as $title) {
            $subtitlesCount = rand(3, 6); // each title have 3-6 subtitles
            
            for ($j = 0; $j < $subtitlesCount && $documentCount < 100; $j++) {
                $subtitle = fake()->unique()->sentence(3);
                $status = fake()->randomElement([InformationCentreStatus::Published, InformationCentreStatus::Draft]);
                $publishedOn = now()->subDays(rand(1, 365));
                
                InformationCentre::create([
                    'title'        => $title,
                    'subtitle'     => $subtitle,
                    'filename'     => Str::slug($subtitle) . '.pdf',
                    'status'       => $status,
                    'published_on' => $status === InformationCentreStatus::Published ? $publishedOn : null,
                ]);
                
                $documentCount++;
            }
        }
    }
}
