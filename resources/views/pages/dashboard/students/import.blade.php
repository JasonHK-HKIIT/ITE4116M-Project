<?php

use App\Enums\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

new
#[Layout('layouts::dashboard')]
class extends Component
{
    use Toast;
    use WithFileUploads;

    public ?TemporaryUploadedFile $csv = null;

    public array $logs = [];

    public int $processedRows = 0;

    public int $importedRows = 0;

    public int $skippedRows = 0;

    public function render()
    {
        return $this->view()->title(trans('students.import.title'));
    }

    public function import(): void
    {
        $this->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ]);

        [$headers, $rows] = $this->readCsv($this->csv);

        if (in_array('password', $headers, true))
        {
            $this->error(trans('students.import.messages.password_column_not_allowed'));

            return;
        }

        if ($headers !== $this->expectedHeaders())
        {
            $this->error(trans('students.import.messages.invalid_header'));

            return;
        }

        $this->resetImportStats();

        foreach ($rows as $rowNumber => $rowValues)
        {
            $this->processedRows++;

            $row = $this->normalizeRow(array_combine($headers, $rowValues) ?: []);
            $username = (string) ($row['username'] ?? '-');

            try
            {
                $validated = $this->validateRow($row);

                DB::transaction(function () use ($validated)
                {
                    $user = new User();
                    $user->username = $validated['username'];
                    $user->password = Str::random(16);
                    $user->family_name = $validated['family_name'];
                    $user->given_name = $validated['given_name'];
                    $user->chinese_name = $validated['chinese_name'];
                    $user->role = Role::STUDENT;
                    $user->save();

                    $student = new Student();
                    $student->fill([
                        'gender' => $validated['gender'],
                        'date_of_birth' => $validated['date_of_birth'],
                        'nationality' => $validated['nationality'],
                        'mother_tongue' => $validated['mother_tongue'],
                        'tel_no' => $validated['tel_no'],
                        'mobile_no' => $validated['mobile_no'],
                        'address' => $validated['address'],
                        'institute_id' => $validated['institute_id'],
                        'campus_id' => $validated['campus_id'],
                    ]);
                    $student->user()->associate($user);
                    $student->save();

                    $student->classes()->sync($validated['class_ids'] ?? []);
                });

                $this->importedRows++;
                $this->addLog($rowNumber, $username, 'imported', trans('students.import.logs.imported'));
            }
            catch (ValidationException $exception)
            {
                $this->skippedRows++;
                $message = collect($exception->errors())->flatten()->first() ?? 'Invalid row.';
                $this->addLog($rowNumber, $username, 'skipped', (string) $message);
            }
            catch (\Throwable $exception)
            {
                $this->skippedRows++;
                $this->addLog($rowNumber, $username, 'skipped', $exception->getMessage());
            }
        }

        $this->success(trans('students.import.messages.completed', [
            'imported' => $this->importedRows,
            'skipped' => $this->skippedRows,
        ]));
    }

    public function downloadSample()
    {
        return response()->download(
            storage_path('app/samples/students-import-sample.csv'),
            'students-import-sample.csv',
            ['Content-Type' => 'text/csv']
        );
    }

    protected function resetImportStats(): void
    {
        $this->processedRows = 0;
        $this->importedRows = 0;
        $this->skippedRows = 0;
        $this->logs = [];
    }

    protected function readCsv(TemporaryUploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle)
        {
            throw ValidationException::withMessages(['csv' => 'Unable to read uploaded file.']);
        }

        $headers = fgetcsv($handle, escape: '\\') ?: [];

        if (isset($headers[0]))
        {
            $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $headers[0]);
        }

        $headers = array_map(fn ($header) => trim((string) $header), $headers);

        $rows = [];
        $line = 1;

        while (($data = fgetcsv($handle)) !== false)
        {
            $line++;

            if ($this->isEmptyCsvRow($data))
            {
                continue;
            }

            $rows[$line] = array_pad($data, count($headers), null);
        }

        fclose($handle);

        return [$headers, $rows];
    }

    protected function isEmptyCsvRow(array $data): bool
    {
        foreach ($data as $value)
        {
            if (trim((string) $value) !== '')
            {
                return false;
            }
        }

        return true;
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($this->expectedHeaders() as $header)
        {
            $value = $row[$header] ?? null;
            $value = is_string($value) ? trim($value) : $value;
            $normalized[$header] = $value === '' ? null : $value;
        }

        return $normalized;
    }

    protected function validateRow(array $row): array
    {
        $classIds = collect(explode(',', (string) ($row['class_ids'] ?? '')))
            ->map(fn ($value) => trim($value))
            ->filter(fn ($value) => $value !== '')
            ->map(fn ($value) => (int) $value)
            ->values()
            ->all();

        $payload = [
            'username' => $row['username'],
            'family_name' => $row['family_name'],
            'given_name' => $row['given_name'],
            'chinese_name' => $row['chinese_name'],
            'gender' => $row['gender'],
            'date_of_birth' => $row['date_of_birth'],
            'nationality' => $row['nationality'],
            'mother_tongue' => $row['mother_tongue'],
            'tel_no' => $row['tel_no'],
            'mobile_no' => $row['mobile_no'],
            'address' => $row['address'],
            'institute_id' => $row['institute_id'],
            'campus_id' => $row['campus_id'],
            'programme_id' => $row['programme_id'],
            'class_ids' => $classIds,
        ];

        $validator = Validator::make($payload, [
            'username' => ['required', 'alpha_dash:ascii', 'max:255', Rule::unique('users', 'username')],
            'family_name' => ['required', 'max:255'],
            'given_name' => ['required', 'max:255'],
            'chinese_name' => ['nullable', 'max:255'],
            'gender' => ['nullable', 'max:6'],
            'date_of_birth' => ['nullable', 'date'],
            'nationality' => ['nullable', 'max:255'],
            'mother_tongue' => ['nullable', 'max:255'],
            'tel_no' => ['nullable', 'max:255'],
            'mobile_no' => ['nullable', 'max:255'],
            'address' => ['nullable', 'max:255'],
            'institute_id' => ['required', 'integer', 'exists:institutes,id'],
            'campus_id' => [
                'required',
                'integer',
                Rule::exists('campuses', 'id'),
                Rule::exists('institute_campus', 'campus_id')->where(fn ($query) => $query->where('institute_id', $payload['institute_id'])),
            ],
            'programme_id' => [
                'required',
                'integer',
                Rule::exists('programmes', 'id')->where(fn ($query) => $query->where('institute_id', $payload['institute_id'])),
            ],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => [
                'required',
                'integer',
                Rule::exists('classes', 'id')->where(fn ($query) => $query
                    ->where('institute_id', $payload['institute_id'])
                    ->where('campus_id', $payload['campus_id'])
                    ->where('programme_id', $payload['programme_id'])
                ),
            ],
        ]);

        return $validator->validate();
    }

    protected function addLog(int $row, string $username, string $status, string $message): void
    {
        $this->logs[] = [
            'row' => $row,
            'username' => $username,
            'status' => $status,
            'message' => $message,
        ];
    }

    public function expectedHeaders(): array
    {
        return [
            'username',
            'family_name',
            'given_name',
            'chinese_name',
            'gender',
            'date_of_birth',
            'nationality',
            'mother_tongue',
            'tel_no',
            'mobile_no',
            'address',
            'institute_id',
            'campus_id',
            'programme_id',
            'class_ids',
        ];
    }
}; ?>

<div>
    <x-header :title="__('students.import.title')" :subtitle="__('students.import.subtitle')" separator />

    <x-card shadow>
        <x-form wire:submit="import">
            <div class="grid gap-5">
                <x-file :label="__('students.import.upload_label')" wire:model="csv" accept=".csv,text/csv" />

                <div class="text-sm text-base-content/80 space-y-1">
                    <p>{{ __('students.import.sample.hint') }}</p>
                    <p>{{ __('students.import.sample.columns', ['columns' => implode(', ', array_diff($this->expectedHeaders(), ['password']))]) }}</p>
                </div>
            </div>

            <x-slot:actions>
                <x-button :label="__('students.import.sample.download')" wire:click="downloadSample" icon="fal.download" type="button" />
                <x-button :label="__('navigation.dashboard.batch_import')" type="submit" class="btn-primary" spinner="import" />
            </x-slot:actions>
        </x-form>
    </x-card>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <x-stat
            :title="__('students.import.summary.total')"
            :value="$processedRows"
            icon="fal.list-check"
            color="text-info"
        />
        <x-stat
            :title="__('students.import.summary.imported')"
            :value="$importedRows"
            icon="fal.circle-check"
            color="text-success"
        />
        <x-stat
            :title="__('students.import.summary.skipped')"
            :value="$skippedRows"
            icon="fal.triangle-exclamation"
            color="text-error"
        />
    </div>

    <x-card shadow class="mt-6">
        <x-header :title="__('students.import.logs.title')" separator size="text-lg" />

        @if (empty($logs))
            <p class="text-sm text-base-content/70">{{ __('students.import.logs.empty') }}</p>
        @else
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>{{ __('students.import.logs.row') }}</th>
                            <th>{{ __('students.import.logs.username') }}</th>
                            <th>{{ __('students.import.logs.status') }}</th>
                            <th>{{ __('students.import.logs.message') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td>{{ $log['row'] }}</td>
                                <td>{{ $log['username'] }}</td>
                                <td>
                                    <x-badge :value="trans('students.import.logs.' . $log['status'])" :class="$log['status'] === 'imported' ? 'badge-success badge-soft' : 'badge-error badge-soft'" />
                                </td>
                                <td>{{ $log['message'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</div>
