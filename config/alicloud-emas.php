<?php
return [
    "key" => env("ACCESS_KEY_ID"),
    "secret" => env("ACCESS_KEY_SECRET"),
    "region" => env("REGION_ID"),
    "emas_app_key" => env("EMAS_APP_KEY","23629708"),
    "dev" => env("ALICLOUD_EMAS_DEV", true),
    "ios_device_id" => "f72fb02413304ad8a496b3816c017c3a",  //调试模式用实际设备id,生产环境用ALL
    "android_device_id" => "2c62877f76bd4360884463d053c204f4",  //调试模式用实际设备id,生产环境用ALL
    "client_type" => "https",
];
