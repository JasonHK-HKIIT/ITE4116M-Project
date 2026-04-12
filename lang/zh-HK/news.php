<?php

return [
    'title' => '新聞與公告',
    'empty' => '找不到新聞',
    'back_to_list' => '返回新聞列表',
    'carousel' => [
        'title' => '主頁輪播',
        'subtitle' => '管理主頁輪播內容',
        'create_slide' => '建立輪播項目',
        'update_slide' => '更新輪播項目',
        'fields' => [
            'title' => '標題',
            'description' => '描述',
            'link_url' => '連結 URL',
            'is_active' => '啟用',
            'image' => '圖片',
            'position' => '排序',
        ],
        'table' => [
            'image' => '圖片',
            'title' => '標題',
            'link_url' => '連結 URL',
            'status' => '狀態',
            'position' => '排序',
        ],
        'statuses' => [
            'active' => '啟用',
            'inactive' => '停用',
        ],
        'messages' => [
            'saved' => '輪播項目已儲存。',
            'deleted' => '輪播項目已刪除。',
            'moved' => '輪播排序已更新。',
            'image_required' => '建立新輪播項目時必須上載圖片。',
        ],
    ],

    'subtitles' => [
        'create_article' => '建立文章',
        'update_article' => '更新文章',
    ],

    'filters' => [
        'status' => '狀態',
        'any_status' => '任何',
        'published_after' => '發佈日期（之後）',
        'published_before' => '發佈日期（之前）',
    ],

    'table' => [
        'title' => '標題',
        'status' => '狀態',
        'published_on' => '發佈日期',
    ],

    'fields' => [
        'title' => '標題',
        'slug' => 'Slug',
        'content' => '內容',
        'image' => '圖片',
    ],

    'slug_help' => '文章的唯一識別碼',
    'image_preview' => '預覽',
    'current_image' => '目前圖片',

    'statuses' => [
        'draft' => '草稿',
        'published' => '已發佈',
        'archived' => '已封存',
    ],

    'messages' => [
        'created' => '新聞文章已建立。',
        'updated' => '新聞文章已更新。',
        'deleted' => '新聞文章已刪除。',
    ],
];
