<?php

return [
    'password' => [
        'user' => [
            'title' => '更改密碼',
            'subtitle' => '更新你的帳戶密碼',
            'form' => [
                'current_password' => '目前密碼',
                'new_password' => '新密碼',
                'repeat_new_password' => '再次輸入新密碼',
            ],
            'messages' => [
                'updated' => '密碼已更新。',
            ],
        ],
        'admin' => [
            'title' => '重設密碼',
            'subtitle' => '為非管理員使用者重設密碼',
            'form' => [
                'username' => '使用者名稱',
                'new_password' => '新密碼',
                'repeat_new_password' => '再次輸入新密碼',
            ],
            'messages' => [
                'updated' => '密碼已成功重設。',
                'user_not_found' => '找不到使用者。',
                'admin_not_allowed' => '此頁面不可重設管理員帳戶。',
            ],
        ],
    ],

    'staff' => [
        'title' => '教職員',
        'subtitle' => '系統使用者管理',
        'create_subtitle' => '建立教職員',
        'update_subtitle' => '更新教職員',
        'search_placeholder' => '以使用者名稱或姓名搜尋...',
        'filters' => [
            'role' => '角色',
            'permission' => '權限',
        ],
        'form' => [
            'username' => '使用者名稱',
            'chinese_name' => '中文姓名',
            'family_name' => '姓氏',
            'given_name' => '名字',
            'optional' => '選填',
            'role' => '角色',
            'permissions' => '權限',
        ],
        'table' => [
            'username' => '使用者名稱',
            'name' => '姓名',
            'role' => '角色',
            'permissions' => '權限',
            'created' => '建立日期',
        ],
        'messages' => [
            'updated' => '教職員已更新。',
            'created' => '教職員已建立，系統已自動產生密碼。',
            'deleted' => '教職員已刪除。',
            'delete_in_use' => '教職員正在被使用，無法刪除。',
            'cannot_delete_self' => '你不能刪除自己的帳戶。',
            'cannot_change_own_role' => '你不能將自己的帳戶角色由管理員改為其他角色。',
        ],
    ],
];
