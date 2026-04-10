<?php

return [
    'staff' => [
        'title' => 'Staff Members',
        'subtitle' => 'System User Management',
        'create_subtitle' => 'Create Staff Member',
        'update_subtitle' => 'Update Staff Member',
        'search_placeholder' => 'Search by username or name...',
        'filters' => [
            'role' => 'Role',
            'permission' => 'Permission',
        ],
        'form' => [
            'username' => 'Username',
            'chinese_name' => 'Chinese Name',
            'family_name' => 'Family Name',
            'given_name' => 'Given Name',
            'optional' => 'Optional',
            'role' => 'Role',
            'permissions' => 'Permissions',
        ],
        'table' => [
            'username' => 'Username',
            'name' => 'Name',
            'role' => 'Role',
            'permissions' => 'Permissions',
            'created' => 'Created',
        ],
        'messages' => [
            'updated' => 'Staff member was updated.',
            'created' => 'Staff member was created. A password has been auto-generated.',
            'deleted' => 'Staff member was deleted.',
            'delete_in_use' => 'Staff member cannot be deleted because it is in use.',
            'cannot_delete_self' => 'You cannot delete your own account.',
            'cannot_change_own_role' => 'You cannot change your own account role from Admin.',
        ],
    ],
];
