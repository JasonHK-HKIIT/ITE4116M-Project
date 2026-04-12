<?php

return [
    'title' => '學生',
    'subtitle' => '學生管理',
    'import' => [
        'title' => '批次匯入學生',
        'subtitle' => '透過 CSV 匯入學生資料',
        'upload_label' => 'CSV 檔案',
        'sample' => [
            'hint' => '請使用扁平 CSV，且不包含任何 ID 欄位與 password 欄位。',
            'download' => '下載範例 CSV',
            'columns' => '必要欄位：:columns',
        ],
        'summary' => [
            'total' => '已處理列數',
            'imported' => '已匯入',
            'skipped' => '已略過',
        ],
        'logs' => [
            'title' => '匯入記錄',
            'row' => '列',
            'username' => '使用者名稱',
            'status' => '狀態',
            'message' => '訊息',
            'empty' => '目前沒有記錄。',
            'imported' => '已匯入',
            'skipped' => '已略過',
        ],
        'messages' => [
            'completed' => '匯入完成。成功 :imported 筆，略過 :skipped 筆。',
            'invalid_header' => 'CSV 標題不正確，請使用範例模板。',
            'password_column_not_allowed' => '不允許 password 欄位，密碼會由系統自動產生。',
        ],
    ],

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
