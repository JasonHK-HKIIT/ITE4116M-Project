<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Activity;
use App\Enums\NewsArticleStatus;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Activity::factory()->count(20)->create();

        Activity::create([
            'campus_id'         => 1,
            'activity_type'     => 'Campus Representatives',
            'activity_code'     => 'ACT-001-WS',
            'title:en'          => 'Intro to Laravel',
            'title:zh-HK'       => 'Laravel 入門',
            'title:zh-CN'       => 'Laravel 入门',
            'description:en'    => 'A beginner-friendly workshop introducing Laravel basics.',
            'description:zh-HK' => '初學者友善的Laravel基礎工作坊。',
            'description:zh-CN' => '初学者友好的Laravel基础工作坊。',
            'discipline'        => 'IT',
            'attribute'         => 'Effective Communicators (EC)',
            'instructor'        => 'Tom',
            'responsible_staff' => 'Joe',
            'execution_from'    => '2026-01-15',
            'execution_to'      => '2026-01-20',
            'time_slot_from_date' => '2026-01-15',
            'time_slot_from_time' => '09:30',
            'time_slot_to_date'   => '2026-01-15',
            'time_slot_to_time'   => '10:30',
            'duration_hours'    => 1.0,
            'swpd_programme'    => true,
            'venue'             => 'Room 101',
            'venue_remark:en'   => 'Bring your own laptop',
            'venue_remark:zh-HK' => '請自帶筆記本電腦',
            'venue_remark:zh-CN' => '请自带笔记本电脑',
            'capacity'          => 30,
            'registered'        => 12,
            'total_amount'      => 500.00,
            'included_deposit'  => 100.00,
            'attachment'        => 'intro_laravel.pdf',
            'status'            => NewsArticleStatus::Draft,
        ]);

        // 50 Additional multilingual activities
        $titles = [
            'Web Development Bootcamp', 'Python Programming', 'Data Science Fundamentals',
            'Cloud Computing Essentials', 'Cybersecurity Workshop', 'Mobile App Development',
            'AI and Machine Learning', 'Blockchain Technology', 'DevOps Practices',
            'UI/UX Design Masterclass', 'Business Strategy Seminar', 'Leadership Training',
            'Financial Planning Workshop', 'Project Management Basics', 'Digital Marketing',
            'SEO and Content Creation', 'API Development Guide', 'Database Design',
            'Software Testing Fundamentals', 'Agile Methodology Workshop',
            'JavaScript Advanced Concepts', 'React.js Masterclass', 'Vue.js Essentials',
            'Angular Framework Guide', 'Docker and Kubernetes', 'Microservices Architecture',
            'GraphQL Introduction', 'REST API Security', 'Performance Optimization',
            'Code Review Best Practices', 'Git Workflow Mastery', 'Testing Automation',
            'Continuous Integration/Deployment', 'Team Building Activity', 'Health and Wellness',
            'Stress Management Workshop', 'Communication Skills', 'Time Management Seminar',
            'Career Planning Session', 'Interview Preparation', 'Resume Building',
            'Entrepreneurship Bootcamp', 'Startup Pitching Workshop', 'Innovation Summit',
            'Networking Event', 'Industry Meetup', 'Conference Participation',
            'Certification Exam Prep', 'Excel Mastery', 'Power BI Training'
        ];

        $disciplines = ['IT', 'Business', 'Engineering', 'Arts'];
        $attributes = [
            'Effective Communicators (EC)',
            'Independent Learners (IDL)',
            'Informed and Professionally Competent (IPC)',
            'No need to classify',
            'Positive and Flexible (PF)',
            'Problem-solvers (PS)',
            'Professional, Socially and Globally Responsible (PSG)',
        ];
        $activityTypes = [
            'Campus Representatives',
            'Career Development Activities',
            'Extra-curricular Activities',
            'Language Activities',
            'Other Achievements',
            'Personal Development Activities',
            'Physical Education & Sports',
            'Professional Qualifications',
            'Student Groups',
            'Student Organizations',
            'Volunteer Services',
        ];
        $campuses = [1, 2];
        $instructors = ['Tom', 'Sarah', 'Michael', 'Emma', 'James', 'Lisa', 'David', 'Jennifer'];
        $staff = ['Joe', 'Maria', 'Robert', 'Anna', 'Kevin', 'Nicole', 'Thomas', 'Patricia'];

        $translations = [
            'Web Development Bootcamp' => [
                'zh-HK' => '網絡開發訓練營',
                'zh-CN' => '网络开发训练营'
            ],
            'Python Programming' => [
                'zh-HK' => 'Python 編程',
                'zh-CN' => 'Python 编程'
            ],
            'Data Science Fundamentals' => [
                'zh-HK' => '數據科學基礎',
                'zh-CN' => '数据科学基础'
            ],
            'Cloud Computing Essentials' => [
                'zh-HK' => '雲計算基礎',
                'zh-CN' => '云计算基础'
            ],
            'Cybersecurity Workshop' => [
                'zh-HK' => '網絡安全工作坊',
                'zh-CN' => '网络安全工作坊'
            ],
            'Mobile App Development' => [
                'zh-HK' => '移動應用程序開發',
                'zh-CN' => '移动应用程序开发'
            ],
            'AI and Machine Learning' => [
                'zh-HK' => '人工智能和機器學習',
                'zh-CN' => '人工智能和机器学习'
            ],
            'Blockchain Technology' => [
                'zh-HK' => '區塊鏈技術',
                'zh-CN' => '区块链技术'
            ],
            'DevOps Practices' => [
                'zh-HK' => 'DevOps 實踐',
                'zh-CN' => 'DevOps 实践'
            ],
            'UI/UX Design Masterclass' => [
                'zh-HK' => 'UI/UX 設計大師班',
                'zh-CN' => 'UI/UX 设计大师班'
            ],
            'Business Strategy Seminar' => [
                'zh-HK' => '商業策略研討會',
                'zh-CN' => '商业策略研讨会'
            ],
            'Leadership Training' => [
                'zh-HK' => '領導力培訓',
                'zh-CN' => '领导力培训'
            ],
            'Financial Planning Workshop' => [
                'zh-HK' => '財務規劃工作坊',
                'zh-CN' => '财务规划工作坊'
            ],
            'Project Management Basics' => [
                'zh-HK' => '項目管理基礎',
                'zh-CN' => '项目管理基础'
            ],
            'Digital Marketing' => [
                'zh-HK' => '數字營銷',
                'zh-CN' => '数字营销'
            ],
            'SEO and Content Creation' => [
                'zh-HK' => 'SEO 和內容創作',
                'zh-CN' => 'SEO 和内容创作'
            ],
            'API Development Guide' => [
                'zh-HK' => 'API 開發指南',
                'zh-CN' => 'API 开发指南'
            ],
            'Database Design' => [
                'zh-HK' => '數據庫設計',
                'zh-CN' => '数据库设计'
            ],
            'Software Testing Fundamentals' => [
                'zh-HK' => '軟件測試基礎',
                'zh-CN' => '软件测试基础'
            ],
            'Agile Methodology Workshop' => [
                'zh-HK' => '敏捷方法論工作坊',
                'zh-CN' => '敏捷方法论工作坊'
            ],
            'JavaScript Advanced Concepts' => [
                'zh-HK' => 'JavaScript 進階概念',
                'zh-CN' => 'JavaScript 进阶概念'
            ],
            'React.js Masterclass' => [
                'zh-HK' => 'React.js 大師班',
                'zh-CN' => 'React.js 大师班'
            ],
            'Vue.js Essentials' => [
                'zh-HK' => 'Vue.js 基礎知識',
                'zh-CN' => 'Vue.js 基础知识'
            ],
            'Angular Framework Guide' => [
                'zh-HK' => 'Angular 框架指南',
                'zh-CN' => 'Angular 框架指南'
            ],
            'Docker and Kubernetes' => [
                'zh-HK' => 'Docker 和 Kubernetes',
                'zh-CN' => 'Docker 和 Kubernetes'
            ],
            'Microservices Architecture' => [
                'zh-HK' => '微服務架構',
                'zh-CN' => '微服务架构'
            ],
            'GraphQL Introduction' => [
                'zh-HK' => 'GraphQL 介紹',
                'zh-CN' => 'GraphQL 介绍'
            ],
            'REST API Security' => [
                'zh-HK' => 'REST API 安全性',
                'zh-CN' => 'REST API 安全性'
            ],
            'Performance Optimization' => [
                'zh-HK' => '性能優化',
                'zh-CN' => '性能优化'
            ],
            'Code Review Best Practices' => [
                'zh-HK' => '代碼審查最佳實踐',
                'zh-CN' => '代码审查最佳实践'
            ],
            'Git Workflow Mastery' => [
                'zh-HK' => 'Git 工作流精通',
                'zh-CN' => 'Git 工作流精通'
            ],
            'Testing Automation' => [
                'zh-HK' => '測試自動化',
                'zh-CN' => '测试自动化'
            ],
            'Continuous Integration/Deployment' => [
                'zh-HK' => '持續集成/部署',
                'zh-CN' => '持续集成/部署'
            ],
            'Team Building Activity' => [
                'zh-HK' => '團隊建設活動',
                'zh-CN' => '团队建设活动'
            ],
            'Health and Wellness' => [
                'zh-HK' => '健康和健身',
                'zh-CN' => '健康和健身'
            ],
            'Stress Management Workshop' => [
                'zh-HK' => '壓力管理工作坊',
                'zh-CN' => '压力管理工作坊'
            ],
            'Communication Skills' => [
                'zh-HK' => '溝通技巧',
                'zh-CN' => '沟通技巧'
            ],
            'Time Management Seminar' => [
                'zh-HK' => '時間管理研討會',
                'zh-CN' => '时间管理研讨会'
            ],
            'Career Planning Session' => [
                'zh-HK' => '職業規劃課程',
                'zh-CN' => '职业规划课程'
            ],
            'Interview Preparation' => [
                'zh-HK' => '面試準備',
                'zh-CN' => '面试准备'
            ],
            'Resume Building' => [
                'zh-HK' => '簡歷製作',
                'zh-CN' => '简历制作'
            ],
            'Entrepreneurship Bootcamp' => [
                'zh-HK' => '創業訓練營',
                'zh-CN' => '创业训练营'
            ],
            'Startup Pitching Workshop' => [
                'zh-HK' => '創業演講工作坊',
                'zh-CN' => '创业演讲工作坊'
            ],
            'Innovation Summit' => [
                'zh-HK' => '創新峰會',
                'zh-CN' => '创新峰会'
            ],
            'Networking Event' => [
                'zh-HK' => '網絡活動',
                'zh-CN' => '网络活动'
            ],
            'Industry Meetup' => [
                'zh-HK' => '行業聚會',
                'zh-CN' => '行业聚会'
            ],
            'Conference Participation' => [
                'zh-HK' => '會議參與',
                'zh-CN' => '会议参与'
            ],
            'Certification Exam Prep' => [
                'zh-HK' => '認證考試準備',
                'zh-CN' => '认证考试准备'
            ],
            'Excel Mastery' => [
                'zh-HK' => 'Excel 精通',
                'zh-CN' => 'Excel 精通'
            ],
            'Power BI Training' => [
                'zh-HK' => 'Power BI 培訓',
                'zh-CN' => 'Power BI 培训'
            ],
        ];

        for ($i = 2; $i <= 51; $i++) {
            $titleIndex = ($i - 2) % count($titles);
            $titleEn = $titles[$titleIndex];
            $titleTranslations = $translations[$titleEn] ?? ['zh-HK' => $titleEn, 'zh-CN' => $titleEn];

            Activity::create([
                'campus_id'         => $campuses[array_rand($campuses)],
                'activity_type'     => $activityTypes[array_rand($activityTypes)],
                'activity_code'     => 'ACT-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2)),
                'title:en'          => $titleEn,
                'title:zh-HK'       => $titleTranslations['zh-HK'] ?? $titleEn,
                'title:zh-CN'       => $titleTranslations['zh-CN'] ?? $titleEn,
                'description:en'    => "Comprehensive session on $titleEn covering all essential topics and practical applications.",
                'description:zh-HK' => "關於 {$titleTranslations['zh-HK']} 的全面課程，涵蓋所有基本主題和實際應用。",
                'description:zh-CN' => "关于 {$titleTranslations['zh-CN']} 的全面课程，涵盖所有基本主题和实际应用。",
                'discipline'        => $disciplines[array_rand($disciplines)],
                'attribute'         => $attributes[array_rand($attributes)],
                'instructor'        => $instructors[array_rand($instructors)],
                'responsible_staff' => $staff[array_rand($staff)],
                'execution_from'    => date('Y-m-d', strtotime("+$i days")),
                'execution_to'      => date('Y-m-d', strtotime("+$i days +5 days")),
                'time_slot_from_date' => date('Y-m-d', strtotime("+$i days")),
                'time_slot_from_time' => '09:00',
                'time_slot_to_date'   => date('Y-m-d', strtotime("+$i days")),
                'time_slot_to_time'   => '12:00',
                'duration_hours'    => 3.0,
                'swpd_programme'    => (bool) rand(0, 1),
                'venue'             => "Room " . rand(101, 501),
                'venue_remark:en'   => "Please arrive 15 minutes early. Bring necessary materials.",
                'venue_remark:zh-HK' => "請提前15分鐘到達。攜帶必要的材料。",
                'venue_remark:zh-CN' => "请提前15分钟到达。携带必要的材料。",
                'capacity'          => rand(20, 100),
                'registered'        => rand(5, 50),
                'total_amount'      => rand(500, 5000),
                'included_deposit'  => rand(100, 1000),
                'attachment'        => null,
                'status'            => NewsArticleStatus::Published,
            ]);
        }
    }
}
