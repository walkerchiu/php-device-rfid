<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DeviceRFID: Reader
    |--------------------------------------------------------------------------
    |
    */

    'name'        => '名稱',
    'description' => '描述',
    'location'    => '位置',
    'serial'      => '編號',
    'identifier'  => '識別符',
    'order'       => '順序',
    'is_enabled'  => '是否啟用',

    'slave_id'      => 'Slave ID',
    'ip'            => 'IP 位址',
    'port'          => '連接埠',
    'scan_interval' => '掃描間隔',
    'sync_at'       => '同步時間',
    'is_multiplex'  => '是否多工',

    'list'   => '讀卡機清單',
    'create' => '新增讀卡機',
    'edit'   => '讀卡機修改',

    'form' => [
        'information' => '讀卡機資訊',
            'basicInfo'   => '基本資訊'
    ],

    'delete' => [
        'header' => '刪除讀卡機',
        'body'   => '確定要刪除這臺讀卡機嗎？'
    ]
];
