<?php

return [
    'title' => '學生',
    'subtitle' => '學生管理',
    'search_placeholder' => '以學生編號、姓名或手機搜尋...',
    'filters' => [
        'institute' => '學院',
        'campus' => '校園',
        'programme' => '課程',
    ],
    'table' => [
        'student_id' => '學生編號',
        'name' => '姓名',
        'institute' => '學院',
        'campus' => '校園',
        'classes' => '班別',
        'programmes' => '課程',
    ],
    'form' => [
        'student_id' => '學生編號',
        'chinese_name' => '中文姓名',
        'family_name' => '姓氏',
        'given_name' => '名字',
        'gender' => '性別',
        'date_of_birth' => '出生日期',
        'nationality' => '國籍',
        'mother_tongue' => '母語',
        'telephone_no' => '電話號碼',
        'mobile_no' => '手提電話號碼',
        'address' => '地址',
        'classes' => '班別',
        'optional' => '選填',
        'prefer_not_to_say' => '不願透露',
    ],
    'modal' => [
        'create_student' => '建立學生',
        'update_student' => '更新學生',
    ],
    'messages' => [
        'updated' => '學生已更新。',
        'created' => '學生已建立，系統已自動產生密碼。',
        'deleted' => '學生已刪除。',
        'delete_in_use' => '學生正在被使用，無法刪除。',
    ],
];
