<?php

return [
    'title' => 'Students',
    'subtitle' => 'Student Management',
    'import' => [
        'title' => 'Batch Import Students',
        'subtitle' => 'Import students from CSV',
        'upload_label' => 'CSV File',
        'sample' => [
            'hint' => 'Use a flat CSV without ID columns and without password column.',
            'download' => 'Download Sample CSV',
            'columns' => 'Required columns: :columns',
        ],
        'summary' => [
            'total' => 'Processed Rows',
            'imported' => 'Imported',
            'skipped' => 'Skipped',
        ],
        'logs' => [
            'title' => 'Import Log',
            'row' => 'Row',
            'username' => 'Username',
            'status' => 'Status',
            'message' => 'Message',
            'empty' => 'No logs yet.',
            'imported' => 'Imported',
            'skipped' => 'Skipped',
        ],
        'messages' => [
            'completed' => 'Import completed. Imported :imported row(s), skipped :skipped row(s).',
            'invalid_header' => 'CSV header is invalid. Please use the sample template.',
            'password_column_not_allowed' => 'Password column is not allowed. Passwords are auto-generated.',
        ],
    ],

    'search_placeholder' => 'Search by ID, name, or mobile...',
    'filters' => [
        'institute' => 'Institute',
        'campus' => 'Campus',
        'programme' => 'Programme',
    ],
    'table' => [
        'student_id' => 'Student ID',
        'name' => 'Name',
        'institute' => 'Institute',
        'campus' => 'Campus',
        'classes' => 'Classes',
        'programmes' => 'Programmes',
    ],
    'form' => [
        'student_id' => 'Student ID',
        'chinese_name' => 'Chinese Name',
        'family_name' => 'Family Name',
        'given_name' => 'Given Name',
        'gender' => 'Gender',
        'date_of_birth' => 'Date of Birth',
        'nationality' => 'Nationality',
        'mother_tongue' => 'Mother Tongue',
        'telephone_no' => 'Telephone No.',
        'mobile_no' => 'Mobile No.',
        'address' => 'Address',
        'classes' => 'Classes',
        'optional' => 'Optional',
        'prefer_not_to_say' => 'Prefer not to say',
    ],
    'modal' => [
        'create_student' => 'Create Student',
        'update_student' => 'Update Student',
    ],
    'messages' => [
        'updated' => 'Student was updated.',
        'created' => 'Student was created. A password has been auto-generated.',
        'deleted' => 'Student was deleted.',
        'delete_in_use' => 'Student cannot be deleted because it is in use.',
    ],
];
