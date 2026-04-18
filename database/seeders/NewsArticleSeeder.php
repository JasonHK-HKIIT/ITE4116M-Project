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

        $article = NewsArticle::create(
            [
                'slug' => 'vtc-apprenticeship-learn-and-earn-pathway',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-06'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'VTC Apprenticeship – Learn and Earn Pathway',
                    'content' => '<p>The VTC Apprenticeship Scheme offers a "learn and earn" pathway for students who wish to gain recognised qualifications while developing hands-on workplace experience.</p>
<p>Through structured on-the-job training and related academic study, apprentices will build professional skills, industry knowledge and a strong foundation for future career development.</p>
<p>Details of the participating industries, entry requirements, allowance and progression pathways will be announced in due course via campus notice boards and the VTC website.</p>
<p>Interested students are encouraged to stay tuned and contact their Programme Leader or Student Development Office for more information.</p>
<p>VTC Apprenticeship Office</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => 'VTC 學徒訓練計劃 – 邊學邊賺新出路',
                    'content' => '<p>VTC 學徒訓練計劃為同學提供「一邊學習、一邊賺取收入」的升學及就業出路，讓你在真實工作環境中累積經驗，同時修讀相關課程。</p>
<p>透過有系統的在職培訓及課堂學習，同學可以掌握專業技能及行業知識，為日後投身職場打好基礎。</p>
<p>有關行業範圍、入學條件、學徒薪酬及進修安排等詳情，將稍後透過校內公告及 VTC 網站公布。</p>
<p>有興趣的同學可留意最新消息，或向課程主任／學生發展處查詢。</p>
<p>VTC 學徒訓練辦公室</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => 'VTC 学徒训练计划 – 边学边赚新出路',
                    'content' => '<p>VTC 学徒训练计划为同学提供「一边学习、一边赚取收入」的升学及就业出路，让你在真实工作环境中累积经验，同时修读相关课程。</p>
<p>通过有系统的在职培训及课堂学习，同学可以掌握专业技能和行业知识，为将来踏入职场打下稳固基础。</p>
<p>有关行业范围、入学条件、学徒薪酬及进修安排等详情，将稍后通过校内公告及 VTC 网站公布。</p>
<p>有兴趣的同学可留意最新消息，或向课程主任／学生发展处查询。</p>
<p>VTC 学徒训练办公室</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'support-services-for-students-with-sen',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-06'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Support Services for Students with SEN',
                    'content' => '<p>For AY2026/27, the Student Special Support Office provides various support services for students with Special Educational Needs (SEN).</p>
<p>These services aim to help SEN students adapt to campus life, enhance their learning experience and receive appropriate support throughout their studies.</p>
<p>Details of the support arrangements and application procedures will be announced via campus notices and the VTC website.</p>
<p>Student Special Support Office</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '為有特殊教育需要學生提供的支援服務',
                    'content' => '<p>2026/27學年起，學生特別支援辦事處為有特殊教育需要 (SEN) 的同學提供各項支援服務。</p>
<p>相關服務旨在協助SEN學生適應校園生活、改善學習體驗，並在修讀課程期間獲得適切的支援。</p>
<p>有關支援安排及申請程序的詳情，將透過校內公告及 VTC 網站公布。</p>
<p>學生特別支援辦事處</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '为有特殊教育需要学生提供的支援服务',
                    'content' => '<p>自2026/27学年起，学生特别支援办事处为有特殊教育需要 (SEN) 的同学提供各项支援服务。</p>
<p>相关服务旨在协助SEN学生适应校园生活、提升学习体验，并在修读课程期间获得适切的支援。</p>
<p>有关支援安排及申请程序的详情，将通过校内公告及 VTC 网站公布。</p>
<p>学生特别支援办事处</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'programme-selection-info-day',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-02-10'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Programme Selection Info Day – Register Now!',
                    'content' => '<p>Join our Programme Selection Info Day on <strong>10 May</strong> to plan your study and career pathway.</p>
<p>Highlights include:</p>
<ul>
<li>Analysis of admission requirements and programme choices</li>
<li>One-on-one programme consultation with academic staff</li>
<li>Advice from student support professionals for students and parents</li>
</ul>
<p>Seats are limited – register now and get personalised guidance for your next step.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '選科策略資訊日 – 立即登記參加！',
                    'content' => '<p>「選科策略資訊日」將於<strong>5月10日</strong>舉行，協助同學及家長規劃升學及就業出路。</p>
<p>活動重點：</p>
<ul>
<li>分析收生要求及升學部署</li>
<li>專屬「一對一」課程諮詢</li>
<li>學友社專家及老師為家長提供貼士</li>
</ul>
<p>名額有限，請立即登記參加，為前路作好準備！</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '选科策略资讯日 – 立即登记参加！',
                    'content' => '<p>「选科策略资讯日」将于<strong>5月10日</strong>举行，协助同学及家长规划升学及就业出路。</p>
<p>活动重点：</p>
<ul>
<li>分析录取要求及升学部署</li>
<li>专属「一对一」课程咨询</li>
<li>学友社专家及老师为家长提供贴士</li>
</ul>
<p>名额有限，请立即登记参加，为未来做好准备！</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'dse-results-release-talk-and-workshop',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2025-07-05'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'DSE Results Release Talk & Workshop 2025',
                    'content' => '<p>The DSE Results Release Talk & Workshop 2025 will be held on <strong>5 July (Saturday)</strong>, from <strong>2:00 pm to 5:00 pm</strong>.</p>
<p>Activities include:</p>
<ul>
<li>Strategies for handling HKDSE results and programme selection</li>
<li>Counselling on further studies and career prospects</li>
<li>On-site aptitude and interest assessment</li>
<li>Preview of campus life and learning environment</li>
</ul>
<p>Senior secondary students and parents are welcome to join. Register now to get professional advice before results release.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '文憑試放榜講座暨工作坊2025',
                    'content' => '<p><strong>「文憑試放榜講座暨工作坊2025」</strong>將於<strong>7月5日（星期六）下午2時至5時</strong>舉行。</p>
<p>活動內容包括：</p>
<ul>
<li>分析文憑試放榜策略及選科部署</li>
<li>升學及就業前景諮詢</li>
<li>即場測試專長及潛能</li>
<li>大專校園生活及設施率先了解</li>
</ul>
<p>歡迎中六同學及家長參加，把握機會於放榜前作好準備。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '文凭试放榜讲座暨工作坊2025',
                    'content' => '<p><strong>「文凭试放榜讲座暨工作坊2025」</strong>将于<strong>7月5日（星期六）下午2时至5时</strong>举行。</p>
<p>活动内容包括：</p>
<ul>
<li>分析文凭试放榜策略及选科部署</li>
<li>升学及就业前景咨询</li>
<li>现场测试专长及潜能</li>
<li>抢先了解大专校园生活及设施</li>
</ul>
<p>欢迎中六同学及家长参加，把握机会在放榜前做好准备。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'curriculum-information-and-experience-day-infoday',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2025-11-08'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Curriculum Information & Experience Day (InfoDay)',
                    'content' => '<p>The Curriculum Information & Experience Day (InfoDay) will be held on <strong>8–9 November</strong> and <strong>15–16 November (Fri–Sat)</strong>.</p>
<p>Participating institutions include:</p>
<ul>
<li>Hong Kong Institute of Vocational Education (IVE)</li>
<li>Hong Kong Design Institute (HKDI)</li>
<li>Hong Kong Institute of Information Technology (HKIIT)</li>
<li>International Culinary Institute (ICI)</li>
</ul>
<p>Prospective students can learn about programme information, explore campus facilities, join trial lessons and experience campus life.</p>
<p>All S6 students and parents are welcome. Visit our InfoDay website for registration details.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '課程資訊及體驗日（InfoDay）',
                    'content' => '<p><strong>「課程資訊及體驗日（InfoDay）」</strong>將於<strong>11月8–9日及15–16日（星期五、六）</strong>舉行。</p>
<p>參與院校包括：</p>
<ul>
<li>香港專業教育學院</li>
<li>香港知專設計學院</li>
<li>香港資訊科技學院</li>
<li>國際廚藝學院</li>
</ul>
<p>同學可透過活動了解課程資訊、參觀校園設施、參與體驗課堂，親身感受校園生活。</p>
<p>歡迎中六同學及家長參加，詳情請瀏覽 InfoDay 網頁。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '课程资讯及体验日（InfoDay）',
                    'content' => '<p><strong>「课程资讯及体验日（InfoDay）」</strong>将于<strong>11月8–9日及15–16日（星期五、六）</strong>举行。</p>
<p>参与院校包括：</p>
<ul>
<li>香港专业教育学院</li>
<li>香港知专设计学院</li>
<li>香港资讯科技学院</li>
<li>国际厨艺学院</li>
</ul>
<p>同学可透过活动了解课程资讯、参观校园设施、参与体验课堂，亲身感受校园生活。</p>
<p>欢迎中六同学及家长参加，详情请浏览 InfoDay 网页。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'vtc-career-and-internship-fair-2026',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-02'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'VTC Career and Internship Fair 2026',
                    'content' => '<p>The VTC Career and Internship Fair 2026 will be held on <strong>20 March 2026</strong> at the Multi-purpose Hall.</p>
<p>Students can meet recruiting employers, learn about internship opportunities and submit resumes on-site.</p>
<p>Please bring your student ID and updated CV. Business attire is recommended.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => 'VTC 就業及實習招聘展 2026',
                    'content' => '<p>VTC 就業及實習招聘展 2026 將於<strong>2026年3月20日</strong>在多用途禮堂舉行。</p>
<p>同學可即場與僱主交流，了解實習及就業機會，並遞交履歷。</p>
<p>請攜帶學生證及最新履歷，建議穿著整齊商務服飾出席。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => 'VTC 就业及实习招聘展 2026',
                    'content' => '<p>VTC 就业及实习招聘展 2026 将于<strong>2026年3月20日</strong>在多用途礼堂举行。</p>
<p>同学可现场与雇主交流，了解实习与就业机会，并提交简历。</p>
<p>请携带学生证及最新简历，建议穿着整齐商务服饰出席。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'career-readiness-bootcamp-for-final-year-students',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-05'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Career Readiness Bootcamp for Final-year Students',
                    'content' => '<p>A three-session Career Readiness Bootcamp will be organised for final-year students in April 2026.</p>
<p>Topics include CV writing, interview techniques and workplace communication.</p>
<p>Students who complete all sessions will receive an e-certificate.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '應屆畢業生職涯準備訓練營',
                    'content' => '<p>學院將於2026年4月為應屆畢業生舉辦三節「職涯準備訓練營」。</p>
<p>內容涵蓋履歷撰寫、面試技巧及職場溝通。</p>
<p>完成全部課堂的同學可獲電子證書。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '应届毕业生职涯准备训练营',
                    'content' => '<p>学院将于2026年4月为应届毕业生举办三节「职涯准备训练营」。</p>
<p>内容涵盖简历撰写、面试技巧及职场沟通。</p>
<p>完成全部课程的同学可获电子证书。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'english-enhancement-workshops-summer-term-2026',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-08'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'English Enhancement Workshops (Summer Term 2026)',
                    'content' => '<p>The Language Centre will launch English Enhancement Workshops in Summer Term 2026.</p>
<p>Workshop themes include presentation skills, report writing and speaking confidence.</p>
<p>Enrollment is open from 10 to 24 March via the student portal.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '英語提升工作坊（2026 夏季學期）',
                    'content' => '<p>語文中心將於 2026 夏季學期推出英語提升工作坊。</p>
<p>主題包括簡報技巧、報告寫作及口語自信訓練。</p>
<p>同學可於3月10日至3月24日透過學生平台報名。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '英语提升工作坊（2026 夏季学期）',
                    'content' => '<p>语文中心将于 2026 夏季学期推出英语提升工作坊。</p>
<p>主题包括简报技巧、报告写作及口语自信训练。</p>
<p>同学可于3月10日至3月24日通过学生平台报名。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'library-extended-opening-hours-before-exam',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-12'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Library Extended Opening Hours Before Examination',
                    'content' => '<p>To support students during the revision period, campus libraries will extend opening hours from 1 to 30 April 2026.</p>
<p>From Monday to Friday, libraries will close at 10:00 pm. Weekend opening hours remain unchanged.</p>
<p>Please follow library rules and keep the environment quiet for all users.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '考試前圖書館延長開放時間安排',
                    'content' => '<p>為支援同學溫習，校園圖書館將於2026年4月1日至4月30日延長開放時間。</p>
<p>星期一至五將延長至晚上10時閉館，週末開放時間維持不變。</p>
<p>請同學遵守圖書館規則，保持安靜學習環境。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '考试前图书馆延长开放时间安排',
                    'content' => '<p>为支援同学温习，校园图书馆将于2026年4月1日至4月30日延长开放时间。</p>
<p>星期一至五将延长至晚上10时闭馆，周末开放时间维持不变。</p>
<p>请同学遵守图书馆规则，保持安静学习环境。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'student-wellness-week-mental-health-and-balance',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-15'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Student Wellness Week: Mental Health and Balance',
                    'content' => '<p>Student Wellness Week will run from <strong>6 to 10 April 2026</strong> with talks, workshops and interactive booths.</p>
<p>Topics include stress management, sleep quality and healthy study habits.</p>
<p>Students are welcome to join all activities free of charge.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '學生身心健康周：關注心理與平衡生活',
                    'content' => '<p>學生身心健康周將於<strong>2026年4月6日至10日</strong>舉行，設有講座、工作坊及互動攤位。</p>
<p>主題包括壓力管理、睡眠質素及健康學習習慣。</p>
<p>歡迎同學免費參與所有活動。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '学生身心健康周：关注心理与平衡生活',
                    'content' => '<p>学生身心健康周将于<strong>2026年4月6日至10日</strong>举行，设有讲座、工作坊及互动摊位。</p>
<p>主题包括压力管理、睡眠质量及健康学习习惯。</p>
<p>欢迎同学免费参与所有活动。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'innovation-project-funding-scheme-2026-round-1',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-18'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Innovation Project Funding Scheme 2026 (Round 1)',
                    'content' => '<p>The Innovation Project Funding Scheme 2026 (Round 1) is now open for student applications.</p>
<p>Selected teams may receive seed funding for prototype development and showcase opportunities.</p>
<p>Application deadline: <strong>12 April 2026</strong>. Please submit proposals through your department coordinator.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '2026 創新項目資助計劃（第一輪）',
                    'content' => '<p>2026 創新項目資助計劃（第一輪）現正接受學生申請。</p>
<p>獲選隊伍可獲種子資助，用於原型開發及成果展示。</p>
<p>截止日期為<strong>2026年4月12日</strong>，請經學系統籌老師遞交計劃書。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '2026 创新项目资助计划（第一轮）',
                    'content' => '<p>2026 创新项目资助计划（第一轮）现正接受学生申请。</p>
<p>获选队伍可获种子资助，用于原型开发及成果展示。</p>
<p>截止日期为<strong>2026年4月12日</strong>，请经学系统筹老师提交计划书。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'cybersecurity-awareness-seminar-safe-digital-campus',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-21'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Cybersecurity Awareness Seminar: Safe Digital Campus',
                    'content' => '<p>An online Cybersecurity Awareness Seminar will be held on <strong>28 March 2026</strong>.</p>
<p>The seminar will cover phishing prevention, password safety and account protection practices.</p>
<p>Students are encouraged to join and strengthen their digital security awareness.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '網絡安全講座：建立安全數碼校園',
                    'content' => '<p>網上網絡安全講座將於<strong>2026年3月28日</strong>舉行。</p>
<p>內容包括防範釣魚詐騙、密碼安全及帳戶保護措施。</p>
<p>歡迎同學踴躍參與，提升個人數碼安全意識。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '网络安全讲座：建立安全数字校园',
                    'content' => '<p>线上网络安全讲座将于<strong>2026年3月28日</strong>举行。</p>
<p>内容包括防范网络钓鱼诈骗、密码安全及账户保护措施。</p>
<p>欢迎同学踊跃参与，提升个人数字安全意识。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'green-campus-volunteer-recruitment-drive-2026',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-24'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Green Campus Volunteer Recruitment Drive 2026',
                    'content' => '<p>The Green Campus team is recruiting student volunteers for environmental initiatives in Semester 2.</p>
<p>Volunteer roles include waste reduction campaigns, recycling promotion and event support.</p>
<p>Interested students may register by 5 April 2026 through the Student Development Office.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '綠色校園義工招募 2026',
                    'content' => '<p>綠色校園團隊現正招募第二學期環保活動學生義工。</p>
<p>義工崗位包括減廢宣傳、回收推廣及活動支援。</p>
<p>有興趣同學請於2026年4月5日前向學生發展處報名。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '绿色校园义工招募 2026',
                    'content' => '<p>绿色校园团队现正招募第二学期环保活动学生义工。</p>
<p>义工岗位包括减废宣传、回收推广及活动支援。</p>
<p>有兴趣同学请于2026年4月5日前向学生发展处报名。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'alumni-sharing-series-industry-insights-2026',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-26'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Alumni Sharing Series: Industry Insights 2026',
                    'content' => '<p>The Alumni Sharing Series will invite graduates from different sectors to share career journeys and industry insights.</p>
<p>The first session starts on <strong>9 April 2026</strong> in Lecture Theatre 3.</p>
<p>Seats are limited and registration is required.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '校友分享系列：行業發展前瞻 2026',
                    'content' => '<p>校友分享系列將邀請不同行業畢業校友分享職涯歷程及行業趨勢。</p>
<p>首場講座將於<strong>2026年4月9日</strong>在三號演講廳舉行。</p>
<p>名額有限，須預先登記。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '校友分享系列：行业发展前瞻 2026',
                    'content' => '<p>校友分享系列将邀请不同行业毕业校友分享职涯历程及行业趋势。</p>
<p>首场讲座将于<strong>2026年4月9日</strong>在三号演讲厅举行。</p>
<p>名额有限，须预先登记。</p>',
                ],
            ]);

        $article = NewsArticle::create(
            [
                'slug' => 'graduation-photo-day-arrangements-2026',
                'status' => NewsArticleStatus::Published,
                'published_on' => Carbon::parse('2026-03-29'),
            ]);
        $article->newsArticleTranslation()->createMany(
            [
                [
                    'locale' => 'en',
                    'title' => 'Graduation Photo Day Arrangements 2026',
                    'content' => '<p>Graduation Photo Day 2026 will take place from <strong>20 to 22 April 2026</strong>.</p>
<p>Please check your department schedule for session time and gown collection details.</p>
<p>Graduating students should arrive 30 minutes early for registration.</p>',
                ],
                [
                    'locale' => 'zh-HK',
                    'title' => '2026 畢業照拍攝日安排',
                    'content' => '<p>2026 畢業照拍攝日將於<strong>2026年4月20日至22日</strong>舉行。</p>
<p>請留意學系公布的拍攝時段及畢業袍領取安排。</p>
<p>畢業同學請提前30分鐘到場辦理報到。</p>',
                ],
                [
                    'locale' => 'zh-CN',
                    'title' => '2026 毕业照拍摄日安排',
                    'content' => '<p>2026 毕业照拍摄日将于<strong>2026年4月20日至22日</strong>举行。</p>
<p>请留意学系公布的拍摄时段及毕业袍领取安排。</p>
<p>毕业同学请提前30分钟到场办理报到。</p>',
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
