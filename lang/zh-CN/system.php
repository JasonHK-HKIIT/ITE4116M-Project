<?php

return [
    'password' => [
        'user' => [
            'title' => '更改密码',
            'subtitle' => '更新你的账户密码',
            'form' => [
                'current_password' => '当前密码',
                'new_password' => '新密码',
                'repeat_new_password' => '再次输入新密码',
            ],
            'messages' => [
                'updated' => '密码已更新。',
            ],
        ],
        'admin' => [
            'title' => '重设密码',
            'subtitle' => '为非管理员用户重设密码',
            'form' => [
                'username' => '用户名',
                'new_password' => '新密码',
                'repeat_new_password' => '再次输入新密码',
            ],
            'messages' => [
                'updated' => '密码已成功重设。',
                'user_not_found' => '找不到用户。',
                'admin_not_allowed' => '此页面不可重设管理员账户。',
            ],
        ],
    ],

    'staff' => [
        'title' => '教职员',
        'subtitle' => '系统用户管理',
        'create_subtitle' => '建立教职员',
        'update_subtitle' => '更新教职员',
        'search_placeholder' => '以用户名或姓名搜索...',
        'filters' => [
            'role' => '角色',
            'permission' => '权限',
        ],
        'form' => [
            'username' => '用户名',
            'chinese_name' => '中文姓名',
            'family_name' => '姓氏',
            'given_name' => '名字',
            'optional' => '选填',
            'role' => '角色',
            'permissions' => '权限',
        ],
        'table' => [
            'username' => '用户名',
            'name' => '姓名',
            'role' => '角色',
            'permissions' => '权限',
            'created' => '建立日期',
        ],
        'messages' => [
            'updated' => '教职员已更新。',
            'created' => '教职员已建立，系统已自动生成密码。',
            'deleted' => '教职员已删除。',
            'delete_in_use' => '教职员正在被使用，无法删除。',
            'cannot_delete_self' => '你不能删除自己的账户。',
            'cannot_change_own_role' => '你不能将自己的账户角色由管理员改为其他角色。',
        ],
    ],
];
