<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceRFID: Card
    |--------------------------------------------------------------------------
    |
    */

    'name'        => '名稱',
    'description' => '描述',
    'reader_id'   => '讀卡機 ID',
    'user_id'     => '持有者 ID',
    'status_id'   => '狀態 ID',
    'level_id'    => '等級 ID',
    'serial'      => '編號',
    'identifier'  => '卡片內碼',
    'username'    => '使用者',
    'is_black'    => '是否為黑名單',
    'is_enabled'  => '是否啟用',

    'begin_at'     => '開始時間',
    'end_at'       => '結束時間',
    'only_dayType' => '限定日別',
    'exclude_date' => '排除日期',
    'exclude_time' => '排除時間',

    'list'   => '卡片清單',
    'create' => '新增卡片',
    'edit'   => '卡片修改',

    'form' => [
        'information' => '卡片資訊',
            'basicInfo'   => '基本資訊'
    ],

    'delete' => [
        'header' => '刪除卡片',
        'body'   => '確定要刪除這張卡片嗎？'
    ]
];
