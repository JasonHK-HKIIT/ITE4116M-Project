<?php

return [
    'title' => '新闻与公告',
    'empty' => '找不到新闻',
    'back_to_list' => '返回新闻列表',
    'carousel' => [
        'title' => '主页轮播',
        'subtitle' => '管理主页轮播内容',
        'create_slide' => '建立轮播项目',
        'update_slide' => '更新轮播项目',
        'fields' => [
            'title' => '标题',
            'description' => '描述',
            'link_url' => '链接 URL',
            'is_active' => '启用',
            'image' => '图片',
            'position' => '排序',
        ],
        'table' => [
            'image' => '图片',
            'title' => '标题',
            'link_url' => '链接 URL',
            'status' => '状态',
            'position' => '排序',
        ],
        'statuses' => [
            'active' => '启用',
            'inactive' => '停用',
        ],
        'messages' => [
            'saved' => '轮播项目已保存。',
            'deleted' => '轮播项目已删除。',
            'moved' => '轮播排序已更新。',
            'image_required' => '建立新轮播项目时必须上传图片。',
        ],
    ],

    'subtitles' => [
        'create_article' => '建立文章',
        'update_article' => '更新文章',
    ],

    'filters' => [
        'status' => '状态',
        'any_status' => '任何',
        'published_after' => '发布日期（之后）',
        'published_before' => '发布日期（之前）',
    ],

    'table' => [
        'title' => '标题',
        'status' => '状态',
        'published_on' => '发布日期',
    ],

    'fields' => [
        'title' => '标题',
        'slug' => 'Slug',
        'content' => '内容',
        'image' => '图片',
    ],

    'slug_help' => '文章的唯一识别码',
    'image_preview' => '预览',
    'current_image' => '当前图片',

    'statuses' => [
        'draft' => '草稿',
        'published' => '已发布',
        'archived' => '已封存',
    ],

    'messages' => [
        'created' => '新闻文章已建立。',
        'updated' => '新闻文章已更新。',
        'deleted' => '新闻文章已删除。',
    ],
];
