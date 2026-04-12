<?php

return [
    'title' => '学生',
    'subtitle' => '学生管理',
    'import' => [
        'title' => '批量导入学生',
        'subtitle' => '通过 CSV 导入学生资料',
        'upload_label' => 'CSV 文件',
        'sample' => [
            'hint' => '请使用扁平 CSV，且不包含任何 ID 字段与 password 字段。',
            'download' => '下载示例 CSV',
            'columns' => '必填字段：:columns',
        ],
        'summary' => [
            'total' => '已处理行数',
            'imported' => '已导入',
            'skipped' => '已跳过',
        ],
        'logs' => [
            'title' => '导入记录',
            'row' => '行',
            'username' => '用户名',
            'status' => '状态',
            'message' => '消息',
            'empty' => '目前没有记录。',
            'imported' => '已导入',
            'skipped' => '已跳过',
        ],
        'messages' => [
            'completed' => '导入完成。成功 :imported 条，跳过 :skipped 条。',
            'invalid_header' => 'CSV 标题不正确，请使用示例模板。',
            'password_column_not_allowed' => '不允许 password 字段，密码会由系统自动生成。',
        ],
    ],

    'search_placeholder' => '以学生编号、姓名或手机搜索...',
    'filters' => [
        'institute' => '学院',
        'campus' => '校园',
        'programme' => '课程',
    ],
    'table' => [
        'student_id' => '学生编号',
        'name' => '姓名',
        'institute' => '学院',
        'campus' => '校园',
        'classes' => '班别',
        'programmes' => '课程',
    ],
    'form' => [
        'student_id' => '学生编号',
        'chinese_name' => '中文姓名',
        'family_name' => '姓氏',
        'given_name' => '名字',
        'gender' => '性别',
        'date_of_birth' => '出生日期',
        'nationality' => '国籍',
        'mother_tongue' => '母语',
        'telephone_no' => '电话号码',
        'mobile_no' => '手机号',
        'address' => '地址',
        'classes' => '班别',
        'optional' => '选填',
        'prefer_not_to_say' => '不愿透露',
    ],
    'modal' => [
        'create_student' => '建立学生',
        'update_student' => '更新学生',
    ],
    'messages' => [
        'updated' => '学生已更新。',
        'created' => '学生已建立，系统已自动生成密码。',
        'deleted' => '学生已删除。',
        'delete_in_use' => '学生正在被使用，无法删除。',
    ],
];
