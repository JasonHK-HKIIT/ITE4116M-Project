<?php

namespace Database\Seeders;

use App\Enums\NewsArticleStatus;
use App\Helpers\LocalesHelper;
use App\Models\NewsArticle;
use App\Models\NewsArticleTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article = NewsArticle::create(
            [
                'slug' => 'pro-act-training-development-centre-jewellery-is-inviting-applications-for-the-post-of-student-helper',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-06'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Pro-Act Training & Development Centre (Jewellery) is inviting applications for the post of student helper',
                    'content' => '<p>Pro-Act Training &amp; Development Centre (Jewellery) is inviting applications for the post of student helper to assist in jewellery related activities as Design Helper or Event Helper. Interested students may complete the attached application form and apply before 27 Feb 2026.</p>
<p>(1) Design Helper will assist in design publicity materials by using design software; (2) Event Helper will assist in activities mainly on campus</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '卓越培訓發展中心(珠寶業)誠騁學生助理',
                    'content' => '<p>卓越培訓發展中心(珠寶業)誠騁學生助理於珠寶製作活動中協助相關宣傳設計或作為活動助理，有興趣學生可填妥申請表並於2026年2月27日前申請</p>
<p>(1) 設計助理將協助製作以設計軟件製作宣傳品; (2) 活動助理將於活動中協助，主要在校內進行</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '卓越培训发展中心(珠宝业)诚骋学生助理',
                    'content' => '<p>卓越培训发展中心(珠宝业)诚骋学生助理于珠宝制作活动中协助相关宣传设计或作为活动助理，有兴趣学生可填妥申请表并于2026年2月27日前申请</p>
<p>(1) 设计助理将协助制作以设计软件制作宣传品; (2) 活动助理将于活动中协助，主要在校内进行</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'hksar-government-scholarship-fund-ay-202526-reaching-out-award-roa',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-06'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'HKSAR Government Scholarship Fund AY 2025/26: Reaching Out Award (ROA)',
                    'content' => '<p>Full-time Higher Diploma Students:</p>
<p>The <strong>Reaching Out Award (ROA)</strong> aims to support meritorious students who are nominated by VTC to participate in learning, internship or service programmes, as well as national, regional and international events and competitions that are conducted outside Hong Kong and organized or endorsed by VTC.</p>
<p>ROA is now open for application by students. Please READ the &ldquo;<span style="text-decoration: underline;">Information Sheet</span>&rdquo; and the attached &ldquo;Online Application - Notes to Students&rdquo;, and prepare all necessary supporting documents before filling in the online application form.</p>
<p>Interested students should submit online application(s) by clicking the respective link below.</p>
<table style="border-collapse: collapse; width: 100%; height: 180.8px;" border="1"><colgroup><col style="width: 20%;"><col style="width: 20%;"><col style="width: 40%;"><col style="width: 20%;"></colgroup>
<thead>
<tr>
<th>
<p>Level of Study</p>
</th>
<th>
<p>Scholarship/Award</p>
</th>
<th>
<p>Online Application Form<br>(Use your <span style="text-decoration: underline;">OWN</span> VTC CNA to login)</p>
</th>
<th>
<p>Application Period</p>
</th>
</tr>
</thead>
<tbody>
<tr>
<td>
<p>Higher Diploma</p>
</td>
<td>
<p>ROA <em>(Subvented HD programmes)</em></p>
</td>
<td>
<p><a href="https://forms.office.com/r/ZjEJjs8KcZ">https://forms.office.com/r/ZjEJjs8KcZ</a></p>
</td>
<td>
<p>6 Feb &ndash; 9 Mar 2026</p>
</td>
</tr>
</tbody>
</table>
<p>For enquiries, please contact the office of your teaching department or Campus Secretariat of your campus.</p>
<p>VTC Institutional Advancement Office<br>February 2026</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '香港特別行政區政府奬學金基金 2025/26年度：「外展體驗奬」',
                    'content' => '<p>各位全日制高級文憑學生：</p>
<p><strong>「外展體驗奬」(ROA)</strong> 旨在支持由 VTC 提名的優異學生到香港境外，參加由院校舉辦或認可的學習、實習或服務計劃，以及國家、地區和國際性活動及比賽。</p>
<p>ROA現已開始接受申請。在填寫網上申請表格前，請參閱文件「<span style="text-decoration:underline;">申請須知</span>」及 附夾的「網上申請 - 申請須知」，並準備所有相關證明文件。</p>
<p>有意申請的學生，請點擊以下連結，提交網上申請。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '香港特别行政区政府奬学金基金2025/26年度：「外展体验奬」',
                    'content' => '<p>各位全日制高级文凭学生：</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'pre-approved-offer-of-higher-diploma-programmes-for-vtc-graduates',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-06'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => '“Pre-approved Offer” of Higher Diploma Programmes for VTC Graduates',
                    'content' => '<p>To facilitate your progression to Higher Diploma programmes, we have launched &ldquo;Pre-approved Offer&rdquo; scheme for VTC graduates. You can choose from a wide range of Full-time <strong>Higher Diploma programmes</strong> offered by IVE / HKDI / HKIIT / ICI / CCI / MSTI in AY2026/27.</p>
<p>Submit your programme choices via the <a href="http://s6portal.vtc.edu.hk/">VTC Articulation Portal</a> by 28 February 2026 and application fee is not required. You may get a Higher Diploma programme &ldquo;Pre-approved Offer&rdquo; on 25 March 2026. Act now!</p>
<p>Please visit the <a href="https://www.vtc.edu.hk/admission/en/s6/?tab=higher-diploma">Admission Homepage</a> for programmme information.</p>
<p>VTC Admissions Office</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => 'VTC 畢業生升讀高級文憑課程 -「預先取錄」',
                    'content' => '<p>為協助你們順利升讀高級文憑課程，我們為VTC畢業生推出「預先取錄」計劃。你可透過「<a href="http://s6portal.vtc.edu.hk/">VTC學生升學選科平台</a>」選擇由IVE / HKDI / HKIIT / ICI / CCI / MSTI於2026/27學年開辦的<strong>高級文憑課程</strong>。</p>
<p>於2026年2月28日前遞交課程選擇，無需繳費，並有機會於2026年3月25日獲高級文憑「預先取錄」。請立即行動！</p>
<p>課程資訊請參閱<a href="https://www.vtc.edu.hk/admission/tc/s6/?tab=higher-diploma">入學網頁</a>。</p>
<p>VTC招生處</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => 'VTC 毕业生升读高级文凭课程 -「预先取录」',
                    'content' => '<p>为协助你们顺利升读高级文凭课程，我们为VTC毕业生推出「预先取录」计划。你可透过「<a href="http://s6portal.vtc.edu.hk/">VTC学生升学选科平台</a>」选择由 IVE / HKDI / HKIIT / ICI / CCI / MSTI 于 2026/27 学年开办的<strong>高级文凭课程</strong>。</p>
<p>于2026年2月28日前递交课程选择，无需缴费，并有机会于2026年3月25日获高级文凭「预先取录」。请立即行动！</p>
<p>课程资讯请参阅<a href="https://www.vtc.edu.hk/admission/sc/s6/?tab=higher-diploma">入学网页</a>。</p>
<p>VTC招生处</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'jp-morgan-2026-hong-kong-apprenticeship-program-applications-now-open',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-12'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'J.P. Morgan 2026 Hong Kong Apprenticeship Program – Applications Now Open!',
                    'content' => '<p>We are excited to announce the launch of the 2026 Hong Kong Apprenticeship Program – a unique, one-year opportunity designed exclusively for final-year students of Higher Diploma programs who are eager to begin their careers in the financial industry.</p>
<h2>Eligibility Requirements</h2>
<ul>
<li>Final-year Higher Diploma students graduating by July 2026</li>
<li>Open to all academic disciplines and majors</li>
<li>Able to commit to a full-time, one-year program from August 2026 to July 2027</li>
</ul>
<h2>Application Details</h2>
<p>For more information, please refer to the <a href="https://jpmc.fa.oraclecloud.com/hcmUI/CandidateExperience/en/sites/CX_1001/job/210702256/">application link</a> or the <span style="text-decoration:underline;">Apprenticeship Program Flyer</span>.</p>
<p>Interested students are also invited to join the 2026 Hong Kong Apprenticeship Program Open House on 25 March 2026 – an exclusive event showcasing how the program can be a rewarding career path for Higher Diploma students. Please see the <span style="text-decoration:underline;">Open House Flyer</span> for details.</p>
<h2>Application Deadline</h2>
<p>31 March 2026</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => 'J.P. Morgan 2026 Hong Kong Apprenticeship Program – Applications Now Open!',
                    'content' => '<p>We are excited to announce the launch of the 2026 Hong Kong Apprenticeship Program – a unique, one-year opportunity designed exclusively for final-year students of Higher Diploma programs who are eager to begin their careers in the financial industry.</p>
<h2>Eligibility Requirements</h2>
<ul>
<li>Final-year Higher Diploma students graduating by July 2026</li>
<li>Open to all academic disciplines and majors</li>
<li>Able to commit to a full-time, one-year program from August 2026 to July 2027</li>
</ul>
<h2>Application Details</h2>
<p>For more information, please refer to the <a href="https://jpmc.fa.oraclecloud.com/hcmUI/CandidateExperience/en/sites/CX_1001/job/210702256/">application link</a> or the <span style="text-decoration:underline;">Apprenticeship Program Flyer</span>.</p>
<p>Interested students are also invited to join the 2026 Hong Kong Apprenticeship Program Open House on 25 March 2026 – an exclusive event showcasing how the program can be a rewarding career path for Higher Diploma students. Please see the <span style="text-decoration:underline;">Open House Flyer</span> for details.</p>
<h2>Application Deadline</h2>
<p>31 March 2026</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => 'J.P. Morgan 2026 Hong Kong Apprenticeship Program – Applications Now Open!',
                    'content' => '<p>We are excited to announce the launch of the 2026 Hong Kong Apprenticeship Program – a unique, one-year opportunity designed exclusively for final-year students of Higher Diploma programs who are eager to begin their careers in the financial industry.</p>
<h2>Eligibility Requirements</h2>
<ul>
<li>Final-year Higher Diploma students graduating by July 2026</li>
<li>Open to all academic disciplines and majors</li>
<li>Able to commit to a full-time, one-year program from August 2026 to July 2027</li>
</ul>
<h2>Application Details</h2>
<p>For more information, please refer to the <a href="https://jpmc.fa.oraclecloud.com/hcmUI/CandidateExperience/en/sites/CX_1001/job/210702256/">application link</a> or the <span style="text-decoration:underline;">Apprenticeship Program Flyer</span>.</p>
<p>Interested students are also invited to join the 2026 Hong Kong Apprenticeship Program Open House on 25 March 2026 – an exclusive event showcasing how the program can be a rewarding career path for Higher Diploma students. Please see the <span style="text-decoration:underline;">Open House Flyer</span> for details.</p>
<h2>Application Deadline</h2>
<p>31 March 2026</p>',
                ],
            ]);

        NewsArticle::factory()
            ->count(2)
            ->draft()
            ->has(NewsArticleTranslation::factory()
                ->count(3)
                ->sequence(...Arr::map(LocalesHelper::locales(), fn($item) => ['locale' => $item])))
            ->create();
    }
}
