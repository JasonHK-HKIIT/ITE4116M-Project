<?php

namespace App\Http\Controllers\Api;

use App\Enums\Language;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'locale' => ['nullable', 'string', Rule::in(Language::values())],
        ]);

        $locale = $validated['locale'] ?? app()->getLocale();

        $user = User::query()
            ->with([
                'permissions',
                'student.institute.instituteTranslation' => fn ($query) => $query->where('locale', $locale),
                'student.campus.campusTranslation' => fn ($query) => $query->where('locale', $locale),
                'student.classes.programme.programmeTranslation' => fn ($query) => $query->where('locale', $locale),
            ])
            ->findOrFail((int) $validated['user_id']);

        $payload = [
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'family_name' => $user->family_name,
                'given_name' => $user->given_name,
                'chinese_name' => $user->chinese_name,
                'role' => $user->role?->value,
                'avatar_url' => $user->avatar,
            ],
            'permissions' => $user->permissions->map(fn ($permission) => $permission->permission?->value)->filter()->values(),
        ];

        $student = $user->student;

        if ($student)
        {
            $classes = $student->classes->sortByDesc('academic_year')->values();
            $currentClass = $classes->first();

            $payload['student'] = [
                'id' => $student->id,
                'gender' => $student->gender,
                'date_of_birth' => $student->date_of_birth?->toDateString(),
                'nationality' => $student->nationality,
                'mother_tongue' => $student->mother_tongue,
                'tel_no' => $student->tel_no,
                'mobile_no' => $student->mobile_no,
                'address' => $student->address,
                'institute' => [
                    'id' => $student->institute?->id,
                    'name' => data_get($student, 'institute.instituteTranslation.0.name'),
                ],
                'campus' => [
                    'id' => $student->campus?->id,
                    'name' => data_get($student, 'campus.campusTranslation.0.name'),
                ],
                'current_class' => $currentClass ? [
                    'id' => $currentClass->id,
                    'class_code' => $currentClass->class_code,
                    'academic_year' => $currentClass->academic_year,
                ] : null,
                'programmes' => $classes
                    ->map(fn ($classModel) => $classModel->programme)
                    ->filter()
                    ->unique('id')
                    ->values()
                    ->map(fn ($programme) => [
                        'id' => $programme->id,
                        'programme_code' => $programme->programme_code,
                        'name' => data_get($programme, 'programmeTranslation.0.name'),
                    ]),
            ];
        }

        return response()->json([
            'data' => $payload,
            'meta' => [
                'locale' => $locale,
            ],
        ]);
    }
}
