<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    private Institute $institute;

    private Campus $campus;

    /** @var array<string, int> */
    private array $classIds2024 = [];

    /** @var array<string, int> */
    private array $classIds2025 = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institute = Institute::whereTranslation('name', 'Hong Kong Institute of Information Technology', 'en')->firstOrFail();
        $campus = Campus::whereTranslation('name', 'Lee Wai Lee', 'en')->firstOrFail();
        $programme = Programme::where('programme_code', 'IT114105')->firstOrFail();

        $this->institute = $institute;
        $this->campus = $campus;

        /** @var User */
        $user = User::create(
            [
                'username' => '240155170',
                'password' => 'qwerasdf',
                'family_name' => 'Hui',
                'given_name' => 'Ho Fung Matthew',
                'chinese_name' => '許皓峰',
            ]);
        $user->student()->create(
            [
                'institute_id' => $institute->id,
                'campus_id' => $campus->id,
                'gender' => 'Male',
                'date_of_birth' => '1996-06-03',
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Cantonese',
                'tel_no' => null,
                'mobile_no' => '65557890',
                'address' => 'Flat C, 50/F, Mei Lam Court Phase 10, City One Shatin Sha Tin, New Territories, Hong Kong',
            ]);
        $user->student->classes()->sync(
            [
                ClassModel::where('academic_year', 2024)->where('class_code', '1A')->where('programme_id', $programme->id)->first()->id,
                ClassModel::where('academic_year', 2025)->where('class_code', '2A')->where('programme_id', $programme->id)->first()->id,
            ]);

        /** @var User */
        $user = User::firstOrCreate(
            [
                'username' => '240141706',
                'password' => 'letmein',
                'family_name' => 'KWOK',
                'given_name' => 'Chi Leong'
            ]);
        $user->student()->create(
            [
                'institute_id' => $institute->id,
                'campus_id' => $campus->id,
                'gender' => 'Male',
                'date_of_birth' => '2000-12-25',
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Cantonese',
                'tel_no' => null,
                'mobile_no' => '65534567',
                'address' => 'Flat B, 60/F, Mei Lam Court Phase 20, City Two Shatin Sha Tin, Old Territories, Hong Kong',
            ]);
        $user->student->classes()->sync(
            [
                ClassModel::where('academic_year', 2024)->where('class_code', '1A')->where('programme_id', $programme->id)->first()->id,
                ClassModel::where('academic_year', 2025)->where('class_code', '2A')->where('programme_id', $programme->id)->first()->id,
            ]);

        $this->classIds2024 = [
            '1A' => ClassModel::where('academic_year', 2024)->where('class_code', '1A')->where('programme_id', $programme->id)->first()->id,
            '1B' => ClassModel::where('academic_year', 2024)->where('class_code', '1B')->where('programme_id', $programme->id)->first()->id,
        ];
        $this->classIds2025 = [
            '2A' => ClassModel::where('academic_year', 2025)->where('class_code', '2A')->where('programme_id', $programme->id)->first()->id,
            '2B' => ClassModel::where('academic_year', 2025)->where('class_code', '2B')->where('programme_id', $programme->id)->first()->id,
        ];

        $this->seedStudent(['username' => '249000001', 'password' => 'stud001', 'family_name' => 'Chan', 'given_name' => 'Ka Wai', 'gender' => 'Male', 'date_of_birth' => '2004-01-12', 'mobile_no' => '65000001', 'address' => 'Flat A, 12/F, Harmony Court, Sha Tin, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000002', 'password' => 'stud002', 'family_name' => 'Wong', 'given_name' => 'Man Yee', 'gender' => 'Female', 'date_of_birth' => '2004-02-03', 'mobile_no' => '65000002', 'address' => 'Flat B, 18/F, Prosperity Tower, Kwun Tong, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000003', 'password' => 'stud003', 'family_name' => 'Lam', 'given_name' => 'Tsz Chun', 'gender' => 'Male', 'date_of_birth' => '2004-03-21', 'mobile_no' => '65000003', 'address' => 'Flat C, 9/F, Evergreen House, Tsuen Wan, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000004', 'password' => 'stud004', 'family_name' => 'Lee', 'given_name' => 'Sze Wing', 'gender' => 'Female', 'date_of_birth' => '2004-04-16', 'mobile_no' => '65000004', 'address' => 'Flat D, 22/F, Harbour View Court, Tuen Mun, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000005', 'password' => 'stud005', 'family_name' => 'Ng', 'given_name' => 'Ho Yin', 'gender' => 'Male', 'date_of_birth' => '2004-05-08', 'mobile_no' => '65000005', 'address' => 'Flat E, 7/F, Greenfield Mansion, Fanling, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000006', 'password' => 'stud006', 'family_name' => 'Cheung', 'given_name' => 'Pui Lam', 'gender' => 'Female', 'date_of_birth' => '2004-06-14', 'mobile_no' => '65000006', 'address' => 'Flat F, 15/F, Sunrise Building, Yuen Long, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000007', 'password' => 'stud007', 'family_name' => 'Yeung', 'given_name' => 'Chi Him', 'gender' => 'Male', 'date_of_birth' => '2004-07-19', 'mobile_no' => '65000007', 'address' => 'Flat G, 10/F, Golden Plaza, Tai Po, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000008', 'password' => 'stud008', 'family_name' => 'Lau', 'given_name' => 'Wing Sze', 'gender' => 'Female', 'date_of_birth' => '2004-08-25', 'mobile_no' => '65000008', 'address' => 'Flat H, 30/F, Metro Heights, Tseung Kwan O, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000009', 'password' => 'stud009', 'family_name' => 'Chow', 'given_name' => 'Ka Ho', 'gender' => 'Male', 'date_of_birth' => '2004-09-11', 'mobile_no' => '65000009', 'address' => 'Flat J, 8/F, Lucky House, North Point, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000010', 'password' => 'stud010', 'family_name' => 'Ho', 'given_name' => 'Mei Tung', 'gender' => 'Female', 'date_of_birth' => '2004-10-02', 'mobile_no' => '65000010', 'address' => 'Flat K, 19/F, Beacon Court, Chai Wan, Hong Kong Island, Hong Kong'], '1B', '2B');

        $this->seedStudent(['username' => '249000011', 'password' => 'stud011', 'family_name' => 'Kwan', 'given_name' => 'Yiu Ming', 'gender' => 'Male', 'date_of_birth' => '2004-01-30', 'mobile_no' => '65000011', 'address' => 'Flat L, 14/F, Lakeside Tower, Ma On Shan, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000012', 'password' => 'stud012', 'family_name' => 'Leung', 'given_name' => 'Hiu Yan', 'gender' => 'Female', 'date_of_birth' => '2004-02-18', 'mobile_no' => '65000012', 'address' => 'Flat M, 20/F, Century Court, Kowloon Bay, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000013', 'password' => 'stud013', 'family_name' => 'Tang', 'given_name' => 'Lok Hei', 'gender' => 'Male', 'date_of_birth' => '2004-03-07', 'mobile_no' => '65000013', 'address' => 'Flat N, 11/F, Pinecrest House, Tin Shui Wai, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000014', 'password' => 'stud014', 'family_name' => 'Fung', 'given_name' => 'Yin Ting', 'gender' => 'Female', 'date_of_birth' => '2004-04-24', 'mobile_no' => '65000014', 'address' => 'Flat P, 13/F, Oceanview Building, Kennedy Town, Hong Kong Island, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000015', 'password' => 'stud015', 'family_name' => 'Yip', 'given_name' => 'Chun Kit', 'gender' => 'Male', 'date_of_birth' => '2004-05-15', 'mobile_no' => '65000015', 'address' => 'Flat Q, 17/F, Lotus Garden, Wong Tai Sin, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000016', 'password' => 'stud016', 'family_name' => 'Mak', 'given_name' => 'Oi Lam', 'gender' => 'Female', 'date_of_birth' => '2004-06-05', 'mobile_no' => '65000016', 'address' => 'Flat R, 21/F, Kingland Court, Shek Kip Mei, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000017', 'password' => 'stud017', 'family_name' => 'Poon', 'given_name' => 'Tak Wai', 'gender' => 'Male', 'date_of_birth' => '2004-07-28', 'mobile_no' => '65000017', 'address' => 'Flat S, 6/F, Willow House, Aberdeen, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000018', 'password' => 'stud018', 'family_name' => 'Tsang', 'given_name' => 'Suk Yi', 'gender' => 'Female', 'date_of_birth' => '2004-08-10', 'mobile_no' => '65000018', 'address' => 'Flat T, 16/F, Maple Court, Sham Shui Po, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000019', 'password' => 'stud019', 'family_name' => 'Cheng', 'given_name' => 'Kwan Ho', 'gender' => 'Male', 'date_of_birth' => '2004-09-23', 'mobile_no' => '65000019', 'address' => 'Flat U, 5/F, Harbour Mansion, Quarry Bay, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000020', 'password' => 'stud020', 'family_name' => 'Lo', 'given_name' => 'Nga Yan', 'gender' => 'Female', 'date_of_birth' => '2004-10-14', 'mobile_no' => '65000020', 'address' => 'Flat V, 24/F, Central Point, Sheung Wan, Hong Kong Island, Hong Kong'], '1B', '2B');

        $this->seedStudent(['username' => '249000021', 'password' => 'stud021', 'family_name' => 'Ko', 'given_name' => 'Hin Lung', 'gender' => 'Male', 'date_of_birth' => '2004-01-09', 'mobile_no' => '65000021', 'address' => 'Flat W, 12/F, Riverside Court, Tai Wai, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000022', 'password' => 'stud022', 'family_name' => 'Au', 'given_name' => 'Yuk Ching', 'gender' => 'Female', 'date_of_birth' => '2004-02-27', 'mobile_no' => '65000022', 'address' => 'Flat X, 18/F, Blooming Tower, Hung Hom, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000023', 'password' => 'stud023', 'family_name' => 'Tong', 'given_name' => 'Chak Man', 'gender' => 'Male', 'date_of_birth' => '2004-03-12', 'mobile_no' => '65000023', 'address' => 'Flat Y, 9/F, Amber House, Sai Wan Ho, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000024', 'password' => 'stud024', 'family_name' => 'Kwok', 'given_name' => 'Ting Lok', 'gender' => 'Female', 'date_of_birth' => '2004-04-01', 'mobile_no' => '65000024', 'address' => 'Flat Z, 27/F, Grand Court, Tsim Sha Tsui, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000025', 'password' => 'stud025', 'family_name' => 'Sin', 'given_name' => 'Wai Hong', 'gender' => 'Male', 'date_of_birth' => '2004-05-19', 'mobile_no' => '65000025', 'address' => 'Flat A, 11/F, Sky Garden, Lam Tin, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000026', 'password' => 'stud026', 'family_name' => 'Tam', 'given_name' => 'Hok Lam', 'gender' => 'Female', 'date_of_birth' => '2004-06-26', 'mobile_no' => '65000026', 'address' => 'Flat B, 20/F, Panorama House, Sai Kung, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000027', 'password' => 'stud027', 'family_name' => 'Lui', 'given_name' => 'Yat Fung', 'gender' => 'Male', 'date_of_birth' => '2004-07-13', 'mobile_no' => '65000027', 'address' => 'Flat C, 15/F, Glen Court, To Kwa Wan, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000028', 'password' => 'stud028', 'family_name' => 'Chiu', 'given_name' => 'Man Ting', 'gender' => 'Female', 'date_of_birth' => '2004-08-04', 'mobile_no' => '65000028', 'address' => 'Flat D, 6/F, Riverpark Mansion, Fo Tan, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000029', 'password' => 'stud029', 'family_name' => 'Woo', 'given_name' => 'Chun Fai', 'gender' => 'Male', 'date_of_birth' => '2004-09-29', 'mobile_no' => '65000029', 'address' => 'Flat E, 23/F, Emerald House, Causeway Bay, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000030', 'password' => 'stud030', 'family_name' => 'Yuen', 'given_name' => 'Pui Yi', 'gender' => 'Female', 'date_of_birth' => '2004-10-20', 'mobile_no' => '65000030', 'address' => 'Flat F, 25/F, Union Court, Jordan, Kowloon, Hong Kong'], '1B', '2B');

        $this->seedStudent(['username' => '249000031', 'password' => 'stud031', 'family_name' => 'Ip', 'given_name' => 'Kok Leung', 'gender' => 'Male', 'date_of_birth' => '2004-01-05', 'mobile_no' => '65000031', 'address' => 'Flat G, 10/F, Regal Court, Prince Edward, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000032', 'password' => 'stud032', 'family_name' => 'Suen', 'given_name' => 'Ching Man', 'gender' => 'Female', 'date_of_birth' => '2004-02-22', 'mobile_no' => '65000032', 'address' => 'Flat H, 14/F, Jasper House, Mei Foo, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000033', 'password' => 'stud033', 'family_name' => 'Law', 'given_name' => 'Kin Pong', 'gender' => 'Male', 'date_of_birth' => '2004-03-17', 'mobile_no' => '65000033', 'address' => 'Flat J, 9/F, Green Court, Sha Tin, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000034', 'password' => 'stud034', 'family_name' => 'Chee', 'given_name' => 'Hiu Tung', 'gender' => 'Female', 'date_of_birth' => '2004-04-29', 'mobile_no' => '65000034', 'address' => 'Flat K, 19/F, Peninsula House, Mong Kok, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000035', 'password' => 'stud035', 'family_name' => 'Chung', 'given_name' => 'Tsz Lok', 'gender' => 'Male', 'date_of_birth' => '2004-05-03', 'mobile_no' => '65000035', 'address' => 'Flat L, 7/F, Fortune Plaza, Kwai Chung, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000036', 'password' => 'stud036', 'family_name' => 'Luk', 'given_name' => 'Wai Ling', 'gender' => 'Female', 'date_of_birth' => '2004-06-11', 'mobile_no' => '65000036', 'address' => 'Flat M, 22/F, Horizon Court, Lai Chi Kok, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000037', 'password' => 'stud037', 'family_name' => 'Ma', 'given_name' => 'Yin Ho', 'gender' => 'Male', 'date_of_birth' => '2004-07-06', 'mobile_no' => '65000037', 'address' => 'Flat N, 26/F, Silver House, Tung Chung, Lantau, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000038', 'password' => 'stud038', 'family_name' => 'Hsu', 'given_name' => 'Nga Ching', 'gender' => 'Female', 'date_of_birth' => '2004-08-31', 'mobile_no' => '65000038', 'address' => 'Flat P, 13/F, North Star Court, Tsing Yi, New Territories, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000039', 'password' => 'stud039', 'family_name' => 'Shum', 'given_name' => 'Siu Kiu', 'gender' => 'Male', 'date_of_birth' => '2004-09-15', 'mobile_no' => '65000039', 'address' => 'Flat Q, 8/F, Summit Tower, Sheung Shui, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000040', 'password' => 'stud040', 'family_name' => 'Wan', 'given_name' => 'Yee Lam', 'gender' => 'Female', 'date_of_birth' => '2004-10-27', 'mobile_no' => '65000040', 'address' => 'Flat R, 17/F, Metro Court, Tai Kok Tsui, Kowloon, Hong Kong'], '1B', '2B');

        $this->seedStudent(['username' => '249000041', 'password' => 'stud041', 'family_name' => 'Siu', 'given_name' => 'Ho Pak', 'gender' => 'Male', 'date_of_birth' => '2004-01-16', 'mobile_no' => '65000041', 'address' => 'Flat S, 20/F, Golden View, Wan Chai, Hong Kong Island, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000042', 'password' => 'stud042', 'family_name' => 'Tsui', 'given_name' => 'Pui Yan', 'gender' => 'Female', 'date_of_birth' => '2004-02-08', 'mobile_no' => '65000042', 'address' => 'Flat T, 11/F, Seaside Court, Sai Ying Pun, Hong Kong Island, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000043', 'password' => 'stud043', 'family_name' => 'Koo', 'given_name' => 'Chi Hung', 'gender' => 'Male', 'date_of_birth' => '2004-03-26', 'mobile_no' => '65000043', 'address' => 'Flat U, 16/F, Blossom House, Lok Fu, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000044', 'password' => 'stud044', 'family_name' => 'Fok', 'given_name' => 'Wai Ching', 'gender' => 'Female', 'date_of_birth' => '2004-04-12', 'mobile_no' => '65000044', 'address' => 'Flat V, 24/F, Pearl Court, Kowloon Tong, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000045', 'password' => 'stud045', 'family_name' => 'Ngan', 'given_name' => 'Tsz Him', 'gender' => 'Male', 'date_of_birth' => '2004-05-22', 'mobile_no' => '65000045', 'address' => 'Flat W, 9/F, Unity House, Ho Man Tin, Kowloon, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000046', 'password' => 'stud046', 'family_name' => 'Yau', 'given_name' => 'Mei Kwan', 'gender' => 'Female', 'date_of_birth' => '2004-06-18', 'mobile_no' => '65000046', 'address' => 'Flat X, 28/F, Spring Court, Lei Yue Mun, Kowloon, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000047', 'password' => 'stud047', 'family_name' => 'Lai', 'given_name' => 'Yiu Chun', 'gender' => 'Male', 'date_of_birth' => '2004-07-02', 'mobile_no' => '65000047', 'address' => 'Flat Y, 12/F, Cedar Mansion, Tsuen Wan, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000048', 'password' => 'stud048', 'family_name' => 'Choi', 'given_name' => 'Yan Yi', 'gender' => 'Female', 'date_of_birth' => '2004-08-19', 'mobile_no' => '65000048', 'address' => 'Flat Z, 18/F, Harbour Court, Ap Lei Chau, Hong Kong Island, Hong Kong'], '1B', '2B');
        $this->seedStudent(['username' => '249000049', 'password' => 'stud049', 'family_name' => 'Hung', 'given_name' => 'Kit Ming', 'gender' => 'Male', 'date_of_birth' => '2004-09-08', 'mobile_no' => '65000049', 'address' => 'Flat A, 15/F, Union Mansion, Tai Po, New Territories, Hong Kong'], '1A', '2A');
        $this->seedStudent(['username' => '249000050', 'password' => 'stud050', 'family_name' => 'Pang', 'given_name' => 'Hiu Man', 'gender' => 'Female', 'date_of_birth' => '2004-10-30', 'mobile_no' => '65000050', 'address' => 'Flat B, 21/F, Crystal Court, Kowloon City, Kowloon, Hong Kong'], '1B', '2B');
    }
    private function seedStudent(array $payload, string $classCode2024, string $classCode2025): void
    {
        /** @var User */
        $user = User::firstOrCreate(
            ['username' => $payload['username']],
            [
                'password' => $payload['password'],
                'family_name' => $payload['family_name'],
                'given_name' => $payload['given_name'],
                'chinese_name' => $payload['chinese_name'] ?? null,
            ]
        );

        $user->student()->updateOrCreate(
            [],
            [
                'institute_id' => $this->institute->id,
                'campus_id' => $this->campus->id,
                'gender' => $payload['gender'],
                'date_of_birth' => $payload['date_of_birth'],
                'nationality' => 'Hong Kong SAR China',
                'mother_tongue' => 'Cantonese',
                'tel_no' => null,
                'mobile_no' => $payload['mobile_no'],
                'address' => $payload['address'],
            ]
        );

        $user->student->classes()->sync([
            $this->classIds2024[$classCode2024],
            $this->classIds2025[$classCode2025],
        ]);
    }
}
