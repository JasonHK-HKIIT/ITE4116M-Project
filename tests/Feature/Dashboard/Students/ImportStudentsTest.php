<?php

namespace Tests\Feature\Dashboard\Students;

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ImportStudentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_import_students_from_csv(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $seed = $this->seedAcademicData();

        $csv = $this->csvFile([
            'username,family_name,given_name,chinese_name,gender,date_of_birth,nationality,mother_tongue,tel_no,mobile_no,address,institute_id,campus_id,programme_id,class_ids',
            sprintf('import-001,Chan,Tai Man,,Male,2006-01-15,Chinese,Cantonese,25250000,91234567,"Flat A",%d,%d,%d,"%d"', $seed['institute_id'], $seed['campus_id'], $seed['programme_id'], $seed['class_id']),
        ]);

        $this->actingAs($admin);

        $component = Volt::test('pages::dashboard.students.import')
            ->set('csv', $csv)
            ->call('import');

        $component->assertSet('importedRows', 1);
        $component->assertSet('skippedRows', 0);

        $user = User::query()->where('username', 'import-001')->first();

        $this->assertNotNull($user);
        $this->assertSame(Role::STUDENT, $user->role);

        $student = Student::query()->where('user_id', $user->id)->first();

        $this->assertNotNull($student);
        $this->assertDatabaseHas('student_classes', [
            'student_id' => $student->id,
            'class_id' => $seed['class_id'],
        ]);
    }

    public function test_duplicate_username_row_is_skipped_and_logged(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $seed = $this->seedAcademicData();

        User::factory()->create([
            'username' => 'dup-001',
            'role' => Role::STUDENT,
        ]);

        $csv = $this->csvFile([
            'username,family_name,given_name,chinese_name,gender,date_of_birth,nationality,mother_tongue,tel_no,mobile_no,address,institute_id,campus_id,programme_id,class_ids',
            sprintf('dup-001,Lee,Ka Yan,,Female,2005-11-03,Chinese,Cantonese,25250001,92345678,"Room 1203",%d,%d,%d,"%d"', $seed['institute_id'], $seed['campus_id'], $seed['programme_id'], $seed['class_id']),
        ]);

        $this->actingAs($admin);

        $component = Volt::test('pages::dashboard.students.import')
            ->set('csv', $csv)
            ->call('import');

        $component->assertSet('importedRows', 0);
        $component->assertSet('skippedRows', 1);
        $component->assertSet('processedRows', 1);
    }

    public function test_password_column_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => Role::ADMIN]);
        $seed = $this->seedAcademicData();

        $csv = $this->csvFile([
            'username,password,family_name,given_name,chinese_name,gender,date_of_birth,nationality,mother_tongue,tel_no,mobile_no,address,institute_id,campus_id,programme_id,class_ids',
            sprintf('bad-001,Secret123,Chan,Tai Man,,Male,2006-01-15,Chinese,Cantonese,25250000,91234567,"Flat A",%d,%d,%d,"%d"', $seed['institute_id'], $seed['campus_id'], $seed['programme_id'], $seed['class_id']),
        ]);

        $this->actingAs($admin);

        $component = Volt::test('pages::dashboard.students.import')
            ->set('csv', $csv)
            ->call('import');

        $component->assertSet('processedRows', 0);
        $component->assertSet('importedRows', 0);
        $component->assertSet('skippedRows', 0);

        $this->assertDatabaseMissing('users', ['username' => 'bad-001']);
    }

    public function test_staff_without_students_permission_cannot_access_import_page(): void
    {
        $staff = User::factory()->create(['role' => Role::STAFF]);

        $response = $this->actingAs($staff)->get('/dashboard/students/import');

        $response->assertForbidden();
    }

    protected function csvFile(array $lines): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('students.csv', implode(PHP_EOL, $lines) . PHP_EOL);
    }

    protected function seedAcademicData(): array
    {
        $instituteId = DB::table('institutes')->insertGetId([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $campusId = DB::table('campuses')->insertGetId([
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('institute_campus')->insert([
            'institute_id' => $instituteId,
            'campus_id' => $campusId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $programmeId = DB::table('programmes')->insertGetId([
            'institute_id' => $instituteId,
            'programme_code' => 'IT114105',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $classId = DB::table('classes')->insertGetId([
            'academic_year' => 2025,
            'institute_id' => $instituteId,
            'campus_id' => $campusId,
            'programme_id' => $programmeId,
            'class_code' => '1A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return [
            'institute_id' => $instituteId,
            'campus_id' => $campusId,
            'programme_id' => $programmeId,
            'class_id' => $classId,
        ];
    }
}
