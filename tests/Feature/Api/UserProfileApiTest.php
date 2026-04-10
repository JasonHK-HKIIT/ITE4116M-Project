<?php

namespace Tests\Feature\Api;

use App\Enums\Role;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Institute;
use App\Models\Programme;
use App\Models\Student;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_full_profile_for_student_user(): void
    {
        $institute = Institute::create();
        $institute->instituteTranslation()->create([
            'locale' => 'en',
            'name' => 'Hong Kong Institute of Information Technology',
        ]);

        $campus = Campus::create();
        $campus->campusTranslation()->create([
            'locale' => 'en',
            'name' => 'Lee Wai Lee',
        ]);

        $programme = Programme::create([
            'institute_id' => $institute->id,
            'programme_code' => 'HDSE',
        ]);
        $programme->programmeTranslation()->create([
            'locale' => 'en',
            'name' => 'Higher Diploma in Software Engineering',
        ]);

        $classModel = ClassModel::create([
            'academic_year' => 2025,
            'institute_id' => $institute->id,
            'campus_id' => $campus->id,
            'programme_id' => $programme->id,
            'class_code' => 'IT114105',
        ]);

        $user = User::create([
            'username' => '240155170',
            'password' => 'password',
            'family_name' => 'Chan',
            'given_name' => 'Tai Man',
            'role' => Role::STUDENT,
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'institute_id' => $institute->id,
            'campus_id' => $campus->id,
            'gender' => 'male',
            'date_of_birth' => '2001-02-03',
            'nationality' => 'Chinese',
            'mother_tongue' => 'Cantonese',
            'mobile_no' => '91234567',
            'tel_no' => '26360000',
            'address' => 'Some Address',
        ]);

        $student->classes()->attach($classModel->id);

        $response = $this->getJson("/api/profile?user_id={$user->id}&locale=en");

        $response->assertOk();
        $response->assertJsonPath('data.user.username', '240155170');
        $response->assertJsonPath('data.student.institute.name', 'Hong Kong Institute of Information Technology');
        $response->assertJsonPath('data.student.campus.name', 'Lee Wai Lee');
        $response->assertJsonPath('data.student.current_class.class_code', 'IT114105');
        $response->assertJsonPath('data.student.programmes.0.programme_code', 'HDSE');
    }

    public function test_it_omits_student_key_for_staff_user(): void
    {
        $user = User::create([
            'username' => 'staff01',
            'password' => 'password',
            'family_name' => 'Lee',
            'given_name' => 'Ka Yan',
            'role' => Role::STAFF,
        ]);

        UserPermission::create([
            'user_id' => $user->id,
            'permission' => 'calendar',
        ]);

        $response = $this->getJson("/api/profile?user_id={$user->id}");

        $response->assertOk();
        $response->assertJsonPath('data.user.username', 'staff01');
        $response->assertJsonPath('data.permissions.0', 'calendar');
        $response->assertJsonMissingPath('data.student');
    }

    public function test_it_validates_user_id_exists(): void
    {
        $response = $this->getJson('/api/profile?user_id=999999');

        $response->assertStatus(422);
    }
}
